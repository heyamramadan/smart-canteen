<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        // التاريخ المحدد من المستخدم أو اليوم الحالي
        $selectedDate = $request->input('date') ?? Carbon::now()->format('Y-m-d');
        $startDate = Carbon::parse($selectedDate)->startOfDay();
        $endDate = Carbon::parse($selectedDate)->endOfDay();

        // جلب بيانات الطلبات المكتملة في هذا اليوم
        $orderItems = OrderItem::with(['order.student', 'product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // إحصائيات اليوم
        $totalSales = $orderItems->sum(fn($item) => $item->quantity * $item->price);
        $totalOrders = $orderItems->pluck('order_id')->unique()->count();
        $totalItemsSold = $orderItems->sum('quantity');

        // جلب الكميات المتبقية من المنتجات
       $remainingStock = Product::select('product_id', 'name', 'quantity')->get();


        return view('daily-report', [
            'orderItems' => $orderItems,
            'totalSales' => $totalSales,
            'totalOrders' => $totalOrders,
            'totalItemsSold' => $totalItemsSold,
            'remainingStock' => $remainingStock,
            'selectedDate' => $selectedDate
        ]);
    }
}
