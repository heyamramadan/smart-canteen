<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
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
            'daily_limit' => 'nullable|numeric|min:0' // إضافة حقل سقف الشراء
        ]);

        $wallet = Wallet::where('parent_id', $request->parent_id)->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'parent_id' => $request->parent_id,
                'balance' => 0,
                'daily_limit' => $request->daily_limit ?? 0 // تعيين القيمة الافتراضية
            ]);
        } else {
            // تحديث سقف الشراء إذا تم إدخاله
            if ($request->has('daily_limit')) {
                $wallet->daily_limit = $request->daily_limit;
            }
            $wallet->balance += $request->amount;
            $wallet->save();
        }

        return response()->json(['message' => 'تم شحن الرصيد بنجاح']);
    }
}
