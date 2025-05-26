<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // عرض صفحة إنشاء حساب جديد
    public function create()
    {
        return view('user.createaccount');
    }

    // تخزين بيانات المستخدم الجديدة
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'full_name' => 'required|string|max:255',
            'role' => 'required|in:مسؤول,موظف,ولي أمر',
            'phone_number' => 'nullable|string|max:20',
        ]);

        // إنشاء المستخدم
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'role' => $request->role,
            'phone_number' => $request->phone_number,
        ]);

        // إذا كان "ولي أمر"، أضف إلى جدول parents
        if ($user->role === 'ولي أمر') {
            ParentModel::create([
                'user_id' => $user->user_id,
            ]);
        }

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('createaccount.create')->with('success', 'تم إنشاء الحساب بنجاح');
    }

    public function index()
    {
        $users = User::all(); // استرجاع جميع المستخدمين
        return view('index', compact('users'));
    }

}

