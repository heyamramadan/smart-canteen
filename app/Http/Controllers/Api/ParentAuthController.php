<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ParentAuthController extends Controller
{
    //  تسجيل دخول ولي الامر
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])
            ->where('role', 'ولي أمر')
             ->whereNull('deleted_at')
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'بيانات الدخول غير صحيحة أو المستخدم ليس ولي أمر'
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('parent-token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role'=>$user->role,
            ],
            'token' => $token,
        ]);
    }
    //دالة لتغيير كلمة مرور ولي الأمر
    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:6|confirmed',
    ]);

    $user = $request->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'كلمة المرور الحالية غير صحيحة',
        ], 400);
    }

    $user->password = bcrypt($request->new_password);
    $user->save();

    return response()->json([
        'message' => 'تم تغيير كلمة المرور بنجاح',
    ]);
}

}
