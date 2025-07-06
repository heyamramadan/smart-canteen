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
            'wallet.parent.user',
            'wallet.parent.students'
        ]);

        // 🔍 البحث باسم ولي الأمر أو الطالب
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('wallet.parent.user', function ($q2) use ($search) {
                    $q2->where('full_name', 'like', "%{$search}%");
                })->orWhereHas('wallet.parent.students', function ($q3) use ($search) {
                    $q3->where('full_name', 'like', "%{$search}%");
                });
            });
        }

        // 🔍 فلترة حسب النوع
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

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('Transaction', compact('transactions'));
    }
}
