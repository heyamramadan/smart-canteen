<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
     // جلب مشتريات الطلاب التابعين لولي الأمر
    public function getStudentOrders(Request $request)
    {
        $parent = $request->user();

        if (!$parent) {
            return response()->json(['message' => 'ولي الأمر غير موجود'], 404);
        }

        $orders = Order::with(['orderItems.product', 'student'])
            ->whereIn('student_id', $parent->students->pluck('student_id'))// فقط الطلبات المكتملة
            ->latest()
            ->get();

        return response()->json([
            'message' => 'تم جلب الطلبات بنجاح',
            'orders' => $orders
        ]);
    }

public function getTopSellingProducts(Request $request)
{
    $parent = $request->user();

    // جلب جميع طلاب ولي الأمر
    $studentIds = $parent->students->pluck('student_id');

    // جمع عدد المنتجات المشتراة لكل منتج
    $topProducts = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->join('products', 'order_items.product_id', '=', 'products.product_id')
        ->whereIn('orders.student_id', $studentIds)
        ->select(
            'products.name as product_name',
            DB::raw('SUM(order_items.quantity) as total_quantity')
        )
        ->groupBy('products.name')
        ->orderByDesc('total_quantity')
        ->get();

    $total = $topProducts->sum('total_quantity');

    // حساب النسبة المئوية
    $data = $topProducts->map(function ($item) use ($total) {
        return [
            'name' => $item->product_name,
            'quantity' => $item->total_quantity,
            'percentage' => $total > 0 ? round(($item->total_quantity / $total) * 100, 1) : 0
        ];
    });

    return response()->json([
        'message' => 'تم جلب إحصائيات المنتجات الأكثر شراءً',
        'top_products' => $data
    ]);
}
}
