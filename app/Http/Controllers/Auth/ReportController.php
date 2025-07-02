<?php

namespace App\Http\Controllers\Auth;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // الحصول على البيانات الافتراضية (آخر 30 يوم)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        $orderItems = OrderItem::with(['order.student', 'product'])
            ->whereHas('order', function($q) {
                $q->completed();
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $products = Product::all();

        // حساب الإحصائيات
        $totalSales = $orderItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        $totalOrders = Order::completed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalItemsSold = $orderItems->sum('quantity');

        return view('report', compact(
            'orderItems',
            'products',
            'totalSales',
            'totalOrders',
            'totalItemsSold',
            'startDate',
            'endDate'
        ));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'timePeriod' => 'required|in:day,week,month,custom',
            'fromDate' => 'nullable|required_if:timePeriod,custom|date',
            'toDate' => 'nullable|required_if:timePeriod,custom|date|after_or_equal:fromDate',
            'selectedDate' => 'nullable|required_if:timePeriod,day,week,month|date'
        ]);

        // تحديد الفترة الزمنية بناءً على الاختيار
        switch ($request->timePeriod) {
            case 'day':
                $startDate = Carbon::parse($request->selectedDate)->startOfDay();
                $endDate = Carbon::parse($request->selectedDate)->endOfDay();
                break;

            case 'week':
                $startDate = Carbon::parse($request->selectedDate)->startOfWeek();
                $endDate = Carbon::parse($request->selectedDate)->endOfWeek();
                break;

            case 'month':
                $startDate = Carbon::parse($request->selectedDate)->startOfMonth();
                $endDate = Carbon::parse($request->selectedDate)->endOfMonth();
                break;

            case 'custom':
                $startDate = Carbon::parse($request->fromDate)->startOfDay();
                $endDate = Carbon::parse($request->toDate)->endOfDay();
                break;
        }

        // استعلام للحصول على عناصر الطلبات مع العلاقات
        $orderItems = OrderItem::with(['order.student', 'product'])
            ->whereHas('order', function($q) {
                $q->completed();
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $products = Product::all();

        // حساب الإحصائيات
        $totalSales = $orderItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        $totalOrders = Order::completed()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalItemsSold = $orderItems->sum('quantity');

        return view('report', compact(
            'orderItems',
            'products',
            'totalSales',
            'totalOrders',
            'totalItemsSold',
            'startDate',
            'endDate'
        ));
    }
}
