<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
}
