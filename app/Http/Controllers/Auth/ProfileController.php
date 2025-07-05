<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // عرض صفحة الملف الشخصي
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // تحديث بيانات الملف الشخصي
    public function update(Request $request)
    {
        $user = Auth::user();

        // التحقق الأساسي
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
           'phone_number' => 'nullable|string|max:20',
            'role' => 'required|in:مسؤول,موظف,ولي أمر',
            'profile_image' => 'nullable|image|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => ['nullable', Password::min(6)],
        ], [
            'username.required' => 'حقل اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم مستخدم من قبل.',
            'full_name.required' => 'حقل الاسم الكامل مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل.',
            'profile_image.image' => 'يجب أن تكون الصورة ملف صورة صالح.',
            'profile_image.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميغابايت.',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل.',
        ]);

        // تحديث البيانات الأساسية
        $user->username = $validatedData['username'];
        $user->full_name = $validatedData['full_name'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phone_number'];
        $user->role = $validatedData['role'];

        // التعامل مع تغيير كلمة المرور
        if (!empty($validatedData['new_password'])) {
            // تحقق من كلمة المرور الحالية صحيحة
            if (empty($validatedData['current_password']) || !Hash::check($validatedData['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])->withInput();
            }
            // تحديث كلمة المرور الجديدة
            $user->password = Hash::make($validatedData['new_password']);
        }

        // تحديث الصورة الشخصية
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image_url) {
                Storage::disk('public')->delete($user->profile_image_url);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image_url = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'تم تحديث البيانات بنجاح');
    }
}
