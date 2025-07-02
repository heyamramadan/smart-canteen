<?php

namespace App\Http\Controllers\Auth;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\OrderItemsExport;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    // عدد العناصر المعروضة في كل صفحة
    const ITEMS_PER_PAGE = 15;

    public function index()
    {
        // الحصول على البيانات الافتراضية (آخر 30 يوم)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        // استعلام للحصول على عناصر الطلبات مع التقسيم إلى صفحات
        $orderItems = OrderItem::with(['order.student', 'product'])
            ->whereHas('order', function($q) {
                $q->completed();
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(self::ITEMS_PER_PAGE);

        // حساب الإحصائيات باستخدام استعلامات منفصلة لأداء أفضل
        $stats = $this->calculateStats($startDate, $endDate);

        return view('report', array_merge([
            'orderItems' => $orderItems,
            'products' => Product::all(),
            'startDate' => $startDate,
            'endDate' => $endDate
        ], $stats));
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
        [$startDate, $endDate] = $this->getDateRange($request);

        // استعلام للحصول على عناصر الطلبات مع التقسيم إلى صفحات
        $orderItems = OrderItem::with(['order.student', 'product'])
            ->whereHas('order', function($q) {
                $q->completed();
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(self::ITEMS_PER_PAGE);

        // حساب الإحصائيات
        $stats = $this->calculateStats($startDate, $endDate);

        return view('report', array_merge([
            'orderItems' => $orderItems,
            'products' => Product::all(),
            'startDate' => $startDate,
            'endDate' => $endDate
        ], $stats));
    }

    /**
     * حساب الفترة الزمنية بناءً على نوع الفترة المحددة
     */
    protected function getDateRange(Request $request): array
    {
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

            default:
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();
        }

        return [$startDate, $endDate];
    }

    /**
     * حساب الإحصائيات للفترة المحددة
     */
    protected function calculateStats(Carbon $startDate, Carbon $endDate): array
    {
        return [
            'totalSales' => OrderItem::whereHas('order', function($q) {
                    $q->completed();
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum(DB::raw('quantity * price')),

            'totalOrders' => Order::completed()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),

            'totalItemsSold' => OrderItem::whereHas('order', function($q) {
                    $q->completed();
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('quantity')
        ];
    }

// في ReportController@export
public function export(Request $request)
{
    $orderItems = OrderItem::with(['order.student', 'product'])
    ->whereHas('order', fn($q) => $q->completed())
    ->orderBy('created_at', 'desc')
    ->get();

return Excel::download(
    new OrderItemsExport($orderItems),
    'جميع_الطلبات.xlsx'
);
}
}
