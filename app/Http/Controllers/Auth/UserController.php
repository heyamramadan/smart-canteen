<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

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
                'user_id' => $user->id,
            ]);
        }
return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function index()
    {
        $users = User::whereNull('deleted_at')->get();
        return view('user.index', compact('users'));
    }

        // حدف المستخدم نهائياً
        public function forceDelete($id)
        {
            User::withTrashed()->where('user_id', $id)->forceDelete();
            return redirect()->route('users.archive')->with('success', 'تم حذف المستخدم نهائياً بنجاح');
        }

            // استعادة المستخدم من الأرشيف
            public function restore($id)
            {
                User::withTrashed()->where('user_id', $id)->restore();
                return redirect()->route('users.archive')->with('success', 'تم استعادة المستخدم بنجاح');
            }

            public function destroy($id)
            {
                $user = User::where('user_id', $id)->firstOrFail();
                $user->delete();
                
                return redirect()->route('users.index')->with('success', 'تم أرشفة المستخدم بنجاح');
            }
    public function archive()
    {
        $archivedUsers = User::onlyTrashed()->get();
        return view('user.archive', compact('archivedUsers'));
    }
}

