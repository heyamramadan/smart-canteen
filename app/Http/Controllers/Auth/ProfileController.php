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
        return view('user.profile', data: compact('user'));
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
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => ['nullable', 'min:6', 'confirmed'],
        ], [
            'username.required' => 'حقل اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم مستخدم من قبل.',
            'full_name.required' => 'حقل الاسم الكامل مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل.',
            'profile_image.image' => 'يجب أن تكون الصورة ملف صورة صالح.',
            'profile_image.max' => 'حجم الصورة يجب أن لا يتجاوز 2 ميغابايت.',
            'current_password.required_with' => 'حقل كلمة المرور الحالية مطلوب عند تغيير كلمة المرور.',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل.',
            'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
        ]);
    
        // تحديث البيانات الأساسية
        $user->fill($validatedData);
    
        // التعامل مع تغيير كلمة المرور
        if (!empty($validatedData['new_password'])) {
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])->withInput();
            }
            $user->password = Hash::make($validatedData['new_password']);
        }
    
        // تحديث الصورة الشخصية
        if ($request->hasFile('profile_image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($user->profile_image_url) {
                Storage::disk('public')->delete($user->profile_image_url);
            }
            
            // حفظ الصورة الجديدة
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image_url = $path;
        }
    
        // حفظ جميع التغييرات في عملية واحدة
        $user->save();
    
        // التعامل مع طلبات AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث البيانات بنجاح',
                'profile_image_url' => $user->profile_image_url ? asset('storage/'.$user->profile_image_url) : null
            ]);
        }
    
        return redirect()->route('profile')->with('success', 'تم تحديث البيانات بنجاح');
    }
}
