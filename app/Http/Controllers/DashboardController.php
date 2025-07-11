<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ParentModel;
use App\Models\studentmodel; // أو Student إذا غيرت الاسم
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. جلب الإحصائيات الرقمية
        $studentCount = studentmodel::count();
        $parentCount = User::where('role', 'ولي أمر')->count();
        $employeeCount = User::where('role', 'موظف')->count();
        // جلب إجمالي المبيعات للطلبات المكتملة فقط
        $totalSales = Order::where('status', Order::STATUS_COMPLETED)->sum('total_amount');

        // 2. بيانات الرسوم البيانية

        // أ. المنتجات الأكثر مبيعاً (حسب الكمية)
        $topProducts = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->with('product:product_id,name') // جلب اسم المنتج فقط
            ->take(5) // جلب أفضل 5 منتجات
            ->get();

        // ب. الطلاب الأكثر شراءً (حسب إجمالي المبلغ المدفوع)
        $topStudents = Order::query()
            ->select('student_id', DB::raw('SUM(total_amount) as total_spent'))
            ->where('status', Order::STATUS_COMPLETED)
            ->groupBy('student_id')
            ->orderBy('total_spent', 'desc')
            ->with('student:student_id,full_name') // جلب اسم الطالب فقط
            ->take(5) // جلب أفضل 5 طلاب
            ->get();

        // إرسال جميع البيانات إلى الواجهة
        return view('Dashboard', [
            'studentCount' => $studentCount,
            'parentCount' => $parentCount,
            'employeeCount' => $employeeCount,
            'totalSales' => $totalSales,
            'topProducts' => $topProducts,
            'topStudents' => $topStudents,
        ]);
    }
}