<?php

namespace App\Http\Controllers\Auth;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
 public function index(Request $request)
    {
        $query = WalletTransaction::with([
            'wallet.parent.user',         // للوصول إلى اسم ولي الأمر
            'wallet.parent.students'      // للوصول إلى الطلاب
        ]);

        // فلترة حسب البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('wallet.parent.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // فلترة حسب النوع
        if ($request->type && in_array($request->type, ['إيداع', 'سحب'])) {
            $query->where('type', $request->type);
        }

        // فلترة حسب التاريخ
        if ($request->date) {
            if ($request->date === 'اليوم') {
                $query->whereDate('created_at', now()->toDateString());
            } elseif ($request->date === 'أسبوع') {
                $query->whereBetween('created_at', [now()->subDays(7), now()]);
            } elseif ($request->date === 'شهر') {
                $query->whereMonth('created_at', now()->month);
            }
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('Transaction', compact('transactions'));
    }
}
