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
    // جلب المستخدمين الذين لديهم علاقة مع جدول parents (أي تم إدراجهم كأولياء أمور)
    $parents = User::whereHas('parent') // فقط الذين لديهم سجل parent
                   ->with(['parent.wallet']) // تحميل العلاقة مع parent والمحفظة
                   ->get();

    return view('wallet', compact('parents'));
}

public function charge(Request $request)
{
    $request->validate([
        'parent_id' => 'required|exists:parents,parent_id',
        'amount' => 'required|numeric|min:1',
    ]);

    // جلب المحفظة عن طريق parent_id
    $wallet = Wallet::where('parent_id', $request->parent_id)->first();

    if (!$wallet) {
        // إذا المحفظة غير موجودة، يمكن إنشاء محفظة جديدة (اختياري)
        $wallet = Wallet::create([
            'parent_id' => $request->parent_id,
            'balance' => 0,
        ]);
    }

    // تحديث الرصيد
    $wallet->balance += $request->amount;
    $wallet->save();

    return response()->json(['message' => 'تم شحن الرصيد بنجاح']);
}

}
