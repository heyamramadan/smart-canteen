<?php

namespace App\Http\Controllers\Auth;

use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * عرض سجل المعاملات مع الفلاتر.
     */
  public function index(Request $request)
{
    $transactions = $this->filterTransactions($request)->oldest('created_at')->paginate(10);
    return view('Transaction', compact('transactions'));
}


    /**
     * دالة البحث عبر AJAX.
     */
 public function search(Request $request)
{
    $transactions = $this->filterTransactions($request)->oldest('created_at')->take(50)->get();

    return response()->json($transactions->map(function ($tx) {
        $user = $tx->wallet->user ?? null;

        $balanceBefore = $tx->wallet->balance;
        if ($tx->type === 'إيداع') {
            $balanceBefore -= $tx->amount;
        } else {
            $balanceBefore += abs($tx->amount);
        }

        return [
            'id' => $tx->transaction_id,
            'amount' => number_format(abs($tx->amount), 2),
            'type' => $tx->type,
            'created_at' => $tx->created_at->format('d/m/Y h:i A'),
            'parent_name' => $user->full_name ?? 'ولي أمر محذوف',
            'balance_before' => number_format($balanceBefore, 2),
            'balance_after' => number_format($tx->wallet->balance, 2),
        ];
    }));
}

    private function filterTransactions($request)
{
    $query = WalletTransaction::with(['wallet.user.students']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->whereHas('wallet.user', fn($u) => $u->where('full_name', 'like', "%{$search}%"))
              ->orWhereHas('wallet.user.students', fn($s) => $s->where('full_name', 'like', "%{$search}%"));
        });
    }

    if ($request->filled('type') && in_array($request->type, ['إيداع', 'سحب'])) {
        $query->where('type', $request->type);
    }

    if ($request->filled('day') || $request->filled('month') || $request->filled('year')) {
        $query->where(function ($q) use ($request) {
            if ($request->filled('day')) $q->whereDay('created_at', $request->day);
            if ($request->filled('month')) $q->whereMonth('created_at', $request->month);
            if ($request->filled('year')) $q->whereYear('created_at', $request->year);
        });
    }

    return $query;
}

}
