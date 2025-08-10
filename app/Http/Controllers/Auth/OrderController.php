<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StudentModel;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class OrderController extends Controller
{
    public function create()
    {
        // عرض صفحة واجهة البيع (لا تغيير هنا)
        return view('Point of Sale');
    }

  public function store(Request $request)
{
    if (!Auth::check() || !in_array(Auth::user()->role, ['مسؤول', 'موظف'])) {
        return response()->json(['success' => false, 'message' => 'غير مصرح لك بإتمام هذه العملية'], 403);
    }

    $validated = $request->validate([
        'student_id' => 'required|exists:students,student_id',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,product_id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'total_amount' => 'required|numeric|min:0',
        'pin_code' => 'required|string',
    ]);

    $student = StudentModel::with(['user.wallet', 'bannedProducts'])->findOrFail($validated['student_id']);

    $storedPin   = trim(strval($student->pin_code));
    $receivedPin = trim(strval($validated['pin_code']));

    if ($storedPin !== $receivedPin) {
        return response()->json([
            'success' => false,
            'message' => 'الرقم السري غير صحيح'
        ], 401);
    }

    try {
        $orderResponse = DB::transaction(function () use ($validated, $student) {
            $employee = Auth::user();
            $parentUser = $student->user;

            if (!$parentUser) {
                throw new \Exception('لا يوجد ولي أمر مسجل لهذا الطالب.');
            }

            $bannedProductIds = $student->bannedProducts->pluck('product_id')->toArray();
            foreach ($validated['items'] as $item) {
                if (in_array($item['product_id'], $bannedProductIds)) {
                    $productName = Product::find($item['product_id'])->name;
                    throw new \Exception("لا يمكن شراء المنتج '{$productName}' لأنه ممنوع لهذا الطالب.");
                }
            }

            $wallet = $parentUser->wallet;

            if (!$wallet || $wallet->balance < $validated['total_amount']) {
                throw new \Exception('رصيد المحفظة غير كافٍ لإتمام العملية.');
            }

            $todaySpending = $student->orders()
                                    ->whereDate('created_at', Carbon::today())
                                    ->sum('total_amount');

            if (($todaySpending + $validated['total_amount']) > $student->daily_limit) {
                throw new \Exception("لا يمكن إتمام الطلب، لقد تجاوز الطالب حد الإنفاق اليومي المحدد له وهو: {$student->daily_limit} د.ل");
            }

            $order = Order::create([
                'student_id' => $validated['student_id'],
                'employee_id' => $employee->id,
                'total_amount' => $validated['total_amount'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception('الكمية غير متوفرة للمنتج: ' . $product->name);
                }

                OrderItem::create([
                    'order_id' => $order->order_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                $product->decrement('quantity', $item['quantity']);

                if ($product->quantity - $item['quantity'] <= 0) {
                    $product->update(['is_active' => false]);
                }
            }

            $wallet->decrement('balance', $validated['total_amount']);
            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'amount' => -$validated['total_amount'],
                'type' => 'سحب',
                'created_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'تمت عملية البيع بنجاح',
                'order_id' => $order->order_id,
                'employee' => $employee->full_name
            ];
        });

        return response()->json($orderResponse);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
    }
}

}
