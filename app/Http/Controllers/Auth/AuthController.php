<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){

        $datalogin= $request->validate([
            "username"=> ['required','string'],
            "password"=>['required','string']
        ]);

        if(Auth::attempt($datalogin)){
            $request->session()->regenerate();
            return redirect()->intended('/Dashborad');

        }
        return back()->withErrors([
            'username'=>'اسم المستخدم خطا',
            'password'=>'كلمة السر غير صحيحة'
        ]);
}
}
