<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ParentAuthController extends Controller
{
    public function login(Request $request)
    {
    
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // جلب المستخدم بدور ولي أمر
        $user = User::where('username', $credentials['username'])
            ->where('role', 'ولي أمر')
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'بيانات الدخول غير صحيحة أو المستخدم ليس ولي أمر'
            ], 401);
        }

        // حذف التوكنات القديمة (اختياري)
        $user->tokens()->delete();

        // إنشاء توكن جديد
        $token = $user->createToken('parent-token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
            ],
            'token' => $token,
        ]);
    }
}
