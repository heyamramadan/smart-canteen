<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * عرض جميع أولياء الأمور ومحافظهم.
     */
    public function index()
    {
        // ✅ تعديل: جلب المستخدمين الذين دورهم "ولي أمر" مباشرة مع محافظهم.
        $parents = User::where('role', 'ولي أمر')
                       ->with('wallet') // تحميل علاقة المحفظة مباشرة
                       ->get();

        // تم تغيير اسم المتغير في الواجهة من parents إلى users ليكون أكثر وضوحاً
        return view('wallet', ['users' => $parents]);


    }

    /**
     * شحن رصيد وتحديث حد الإنفاق اليومي لولي الأمر.
     */
    public function charge(Request $request)
    {
        // ✅ تعديل: التحقق من user_id بدلاً من parent_id
        $request->validate([
            'user_id' => 'required|exists:users,id', // التأكد من أن المستخدم موجود في جدول users
            'amount' => 'required|numeric|min:1',
        ]);

        // ✅ تعديل: البحث عن المحفظة باستخدام user_id
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user_id], // الشرط للبحث أو الإنشاء
            ['balance' => 0, 'daily_limit' => 20] // قيم افتراضية عند الإنشاء
        );

        // تحديث الرصيد الحالي
        $wallet->balance += $request->amount;


        $wallet->save(); // حفظ التغييرات

        // تسجيل معاملة الإيداع
        WalletTransaction::create([
            'wallet_id' => $wallet->wallet_id,
            'amount' => $request->amount,
            'type' => 'إيداع',
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'تم شحن الرصيد بنجاح']);
    }
}
