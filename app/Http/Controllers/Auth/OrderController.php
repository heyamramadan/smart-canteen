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
use Carbon\Carbon; // <-- إضافة Carbon للتعامل مع التواريخ

class OrderController extends Controller
{
    public function create()
    {
        // عرض صفحة واجهة البيع (لا تغيير هنا)
        return view('Point of Sale');
    }

    public function store(Request $request)
    {
        // التحقق من صلاحيات المستخدم (مسؤول أو موظف)
        if (!Auth::check() || !in_array(Auth::user()->role, ['مسؤول', 'موظف'])) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك بإتمام هذه العملية'], 403);
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0'
        ]);

        try {
            // استخدام DB::transaction لضمان تنفيذ كل العمليات أو التراجع عنها كلها
            $orderResponse = DB::transaction(function () use ($validated) {
                $employee = Auth::user();
                
                // ✅ تعديل: جلب الطالب مع علاقاته المباشرة (ولي الأمر والمحفظة والمنتجات الممنوعة)
                $student = StudentModel::with(['user.wallet', 'bannedProducts'])->findOrFail($validated['student_id']);
                $parentUser = $student->user; // ولي الأمر هو المستخدم المرتبط بالطالب

                // 1. التحقق من وجود ولي أمر
                if (!$parentUser) {
                    throw new \Exception('لا يوجد ولي أمر مسجل لهذا الطالب.');
                }

                // 2. ✅ جديد: التحقق من المنتجات الممنوعة
                $bannedProductIds = $student->bannedProducts->pluck('product_id')->toArray();
                foreach ($validated['items'] as $item) {
                    if (in_array($item['product_id'], $bannedProductIds)) {
                        $productName = Product::find($item['product_id'])->name;
                        throw new \Exception("لا يمكن شراء المنتج '{$productName}' لأنه ممنوع لهذا الطالب.");
                    }
                }

                $wallet = $parentUser->wallet;

                // 3. التحقق من وجود المحفظة والرصيد الكافي
                if (!$wallet || $wallet->balance < $validated['total_amount']) {
                    throw new \Exception('رصيد المحفظة غير كافٍ لإتمام العملية.');
                }

                // 4. ✅ جديد: التحقق من حد الإنفاق اليومي
                $todaySpending = $student->orders()
                                        ->whereDate('created_at', Carbon::today())
                                        ->where('status', 'completed')
                                        ->sum('total_amount');

                if (($todaySpending + $validated['total_amount']) > $wallet->daily_limit) {
                    throw new \Exception("لا يمكن إتمام الطلب، لقد تجاوز الطالب حد الإنفاق اليومي المحدد له وهو: {$wallet->daily_limit} د.ل");
                }

                // 5. إنشاء الطلب
                $order = Order::create([
                    'student_id' => $validated['student_id'],
                    'employee_id' => $employee->id,
                    'total_amount' => $validated['total_amount'],
                    'status' => 'completed'
                ]);

                // 6. إضافة عناصر الطلب وخصم الكمية من المخزون
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

                // 7. خصم المبلغ من المحفظة وتسجيل المعاملة
                $wallet->decrement('balance', $validated['total_amount']);
                WalletTransaction::create([
                    'wallet_id' => $wallet->wallet_id,
                    'amount' => -$validated['total_amount'], // استخدام قيمة سالبة لتمييز السحب
                    'type' => 'سحب',
                    'reference' => 'شراء من المقصف - طلب رقم ' . $order->order_id,
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
            // في حال حدوث أي خطأ، سيتم التراجع عن كل شيء وإرسال رسالة الخطأ
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}