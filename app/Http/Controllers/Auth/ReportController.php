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
        return view('report');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'product_id' => 'nullable|exists:products,product_id',
            'report_type' => 'required|in:daily,monthly,custom'
        ]);

        $query = OrderItem::with(['order', 'product'])
            ->whereHas('order', function($q) {
                $q->completed();
            });

        // تطبيق الفلترة حسب النوع
        switch ($request->report_type) {
            case 'daily':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'monthly':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'custom':
                if ($request->start_date) {
                    $query->whereDate('created_at', '>=', $request->start_date);
                }
                if ($request->end_date) {
                    $query->whereDate('created_at', '<=', $request->end_date);
                }
                break;
        }

        // فلترة حسب المنتج إذا تم تحديده
        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $orderItems = $query->get();
        $products = Product::all();

        // حساب الإحصائيات
        $totalSales = $orderItems->sum('total');
        $totalItemsSold = $orderItems->sum('quantity');

        return view('admin.reports.index', compact('orderItems', 'products', 'totalSales', 'totalItemsSold'));
    }



}

