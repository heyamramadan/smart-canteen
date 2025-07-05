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

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'role' => 'required|in:مسؤول,موظف,ولي أمر',  
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'profile_image' => 'nullable|image|max:2048',
        ]);

        // تحديث البيانات
        $user->full_name = $validatedData['full_name'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phone_number'];
        $user->role = $validatedData['role'];

        // إذا تم إدخال كلمة مرور جديدة
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
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
