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

    // دالة البحث عبر AJAX مثل الطلاب
   public function search(Request $request)
{
    $search = $request->input('query');
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

    if (!empty($date)) {
        match ($date) {
            'اليوم' => $query->whereDate('created_at', now()->toDateString()),
            'أسبوع' => $query->whereBetween('created_at', [now()->subDays(7), now()]),
            'شهر'   => $query->whereMonth('created_at', now()->month),
            default => null,
        };
    }

    $transactions = $query->orderBy('created_at', 'desc')->take(20)->get();

    return response()->json($transactions->map(function ($tx) {
        return [
            'id' => $tx->id,
            'amount' => $tx->amount,
            'type' => $tx->type,
            'created_at' => $tx->created_at->format('Y-m-d H:i'),
            'parent_name' => $tx->wallet->parent->user->full_name ?? 'ولي غير معروف',
            'student_names' => $tx->wallet->parent->students->pluck('full_name')->implode(', '),
        ];
    }));
}

    }

