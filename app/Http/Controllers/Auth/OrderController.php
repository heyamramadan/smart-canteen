<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StudentModel;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
{
    // عرض صفحة واجهة البيع
    return view('Point of Sale');
}

    public function store(Request $request)
    {
        // التحقق من أن المستخدم موظف
if (!Auth::check() || !in_array(Auth::user()->role, ['مسؤول', 'موظف'])) {
    return response()->json([
        'success' => false,
        'message' => 'غير مصرح لك بإتمام هذه العملية'
    ], 403);
}

        $validated = $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0'
        ]);

        return DB::transaction(function () use ($validated) {
            $user = Auth::user();
            $student = StudentModel::with('parent.wallet')->findOrFail($validated['student_id']);

            if (!$student->parent) {
                throw new \Exception('لا يوجد ولي أمر مسجل لهذا الطالب');
            }

            $wallet = $student->parent->wallet;
            if (!$wallet || $wallet->balance < $validated['total_amount']) {
                throw new \Exception('رصيد المحفظة غير كافي لإتمام العملية');
            }

            $order = Order::create([
                'student_id' => $validated['student_id'],
                'employee_id' => $user->id,
                'total_amount' => $validated['total_amount'],
                'status' => 'completed'
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
            }

            $wallet->decrement('balance', $validated['total_amount']);

            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'amount' => $validated['total_amount'],
                'type' => 'سحب',
                'reference' => 'Order #' . $order->order_id,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تمت عملية البيع بنجاح',
                'order_id' => $order->order_id,
                'employee' => $user->full_name
            ]);
        });
    }
}
