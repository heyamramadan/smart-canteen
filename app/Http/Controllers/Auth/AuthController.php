<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
                $datalogin = $request->validate([
            "username" => ['required', 'string'],
            "password" => ['required', 'string']
        ], [
            'username.required' => 'يرجى إدخال اسم المستخدم',
            'password.required' => 'يرجى إدخال كلمة المرور',
        ]);

        if (Auth::attempt($datalogin)) {
            // منع ولي الأمر من الدخول
            if (Auth::user()->role === 'ولي أمر') {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'غير مسموح لأولياء الأمور بالدخول للنظام'
                ]);
            }

            $request->session()->regenerate();

            // إعادة توجيه حسب الدور
            if (Auth::user()->role === 'مسؤول') {
                return redirect()->intended('/dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
            } elseif (Auth::user()->role === 'موظف') {
                return redirect()->intended('/dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
            }


        }

      return back()->withErrors([
    'login_error' => 'اسم المستخدم أو كلمة المرور غير صحيحة'
]);

    }
    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login'); // أو أي صفحة تريد إعادة التوجيه إليها بعد تسجيل الخروج
}
}
