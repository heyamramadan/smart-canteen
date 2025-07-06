<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $parents = User::whereHas('parent')
                       ->with(['parent.wallet'])
                       ->get();

        return view('wallet', compact('parents'));
    }

    public function charge(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:parents,parent_id',
            'amount' => 'required|numeric|min:1',
            'daily_limit' => 'nullable|numeric|min:0' // سقف الشراء اختياري
        ]);

        // جلب المحفظة أو إنشاء جديدة بدون حفظ
        $wallet = Wallet::firstOrNew(['parent_id' => $request->parent_id]);

        // تحديث الرصيد الحالي
        $wallet->balance = ($wallet->balance ?? 0) + $request->amount;

        // تحديث سقف الشراء إن وجد
        if ($request->has('daily_limit')) {
            $wallet->daily_limit = $request->daily_limit;
        }

        $wallet->save(); // حفظ المحفظة (جديدة أو محدثة)

        // تسجيل معاملة الإيداع
        WalletTransaction::create([
            'wallet_id' => $wallet->wallet_id,
            'amount' => $request->amount,
            'type' => 'إيداع',
            'reference' => 'شحن رصيد بواسطة لوحة التحكم',
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'تم شحن الرصيد بنجاح']);
    }
}
