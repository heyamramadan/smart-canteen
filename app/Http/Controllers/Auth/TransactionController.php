<?php

namespace App\Http\Controllers\Auth;

use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // عرض سجل المعاملات مع الفلاتر
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
     // ✅ فلترة حسب التاريخ: يوم / شهر / سنة
if ($request->filled('day') || $request->filled('month') || $request->filled('year')) {
    $query->where(function ($q) use ($request) {
        if ($request->filled('day')) {
            $q->whereDay('created_at', $request->day);
        }
        if ($request->filled('month')) {
            $q->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $q->whereYear('created_at', $request->year);
        }
    });
}


        $transactions = $query->orderBy('created_at', 'asc')->paginate(10);

        return view('Transaction', compact('transactions'));
    }

    // دالة البحث عبر AJAX مثل الطلاب
    public function search(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $date = $request->input('date');

        $query = WalletTransaction::with([
            'wallet.parent.user',
            'wallet.parent.students'
        ]);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('wallet.parent.user', function ($q2) use ($search) {
                    $q2->where('full_name', 'like', "%{$search}%");
                })->orWhereHas('wallet.parent.students', function ($q3) use ($search) {
                    $q3->where('full_name', 'like', "%{$search}%");
                });
            });
        }

        if (!empty($type) && in_array($type, ['إيداع', 'سحب'])) {
            $query->where('type', $type);
        }

     if ($request->filled('day') || $request->filled('month') || $request->filled('year')) {
    $query->where(function ($q) use ($request) {
        if ($request->filled('day')) {
            $q->whereDay('created_at', $request->day);
        }
        if ($request->filled('month')) {
            $q->whereMonth('created_at', $request->month);
        }
        if ($request->filled('year')) {
            $q->whereYear('created_at', $request->year);
        }
    });
}


        $transactions = $query->orderBy('created_at', 'asc')->take(20)->get();

        return response()->json($transactions->map(function ($tx) {
            $parentUser = $tx->wallet->parent->user ?? null;
            $students = $tx->wallet->parent->students ?? collect();

            $balanceBefore = $tx->type === 'إيداع'
                ? ($tx->amount ? $tx->wallet->balance - $tx->amount : $tx->wallet->balance)
                : ($tx->amount ? $tx->wallet->balance + $tx->amount : $tx->wallet->balance);

            return [
                'id' => $tx->id,
                'transaction_id' => $tx->transaction_id,
                'amount' => $tx->amount,
                'type' => $tx->type,
                'created_at' => $tx->created_at->format('d/m/Y h:i A'),  // نفس تنسيق الصفحة
                'parent_name' => $parentUser?->full_name ?? $parentUser?->name ?? 'ولي غير معروف',
                'student_names' => $students->pluck('full_name')->implode(', '),
                'balance_before' => number_format($balanceBefore, 2),
                'balance_after' => number_format($tx->wallet->balance, 2),
            ];
        }));
    }
}
