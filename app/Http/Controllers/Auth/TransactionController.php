<?php

namespace App\Http\Controllers\Auth;

use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // بدء الاستعلام مع العلاقات للوصول لبيانات ولي الأمر والطلاب
        $query = WalletTransaction::with([
            'wallet.parent.user',     // اسم ولي الأمر
            'wallet.parent.students'  // أسماء الطلاب
        ]);

        // 🔍 فلترة حسب اسم ولي الأمر
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('wallet.parent.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // 🔍 فلترة حسب نوع العملية (إيداع / سحب)
        if ($request->filled('type') && in_array($request->type, ['إيداع', 'سحب'])) {
            $query->where('type', $request->type);
        }

        // 🔍 فلترة حسب التاريخ
        if ($request->filled('date')) {
            match ($request->date) {
                'اليوم' => $query->whereDate('created_at', now()->toDateString()),
                'أسبوع' => $query->whereBetween('created_at', [now()->subDays(7), now()]),
                'شهر'   => $query->whereMonth('created_at', now()->month),
                default => null
            };
        }

        // ترتيب النتائج حسب الأحدث مع التصفح
        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        // عرض الصفحة
        return view('Transaction', compact('transactions'));
    }
}
