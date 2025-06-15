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
                return redirect()->intended('/dashboard');
            } elseif (Auth::user()->role === 'موظف') {
                return redirect()->intended('/point');
            }

            // احتياطاً: إذا لم يكن الدور معروف
            Auth::logout();
            return back()->withErrors([
                'username' => 'دور المستخدم غير معرف'
            ]);
        }

        return back()->withErrors([
            'username' => 'اسم المستخدم خطأ',
            'password' => 'كلمة السر غير صحيحة'
        ]);
    }
}
