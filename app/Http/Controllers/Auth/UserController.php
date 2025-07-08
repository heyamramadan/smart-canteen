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
//عرض
   public function index()
{
   $users = User::withTrashed()->paginate(10); 
    return view('user.index', compact('users'));
}
    public function search(Request $request)
    {
        $searchQuery = $request->input('query');

        $query = User::withTrashed();

        if (!empty($searchQuery)) {
            $isIdSearch = is_numeric($searchQuery);

            $query->where(function($q) use ($searchQuery, $isIdSearch) {
                $q->where('username', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('full_name', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('role', 'LIKE', '%' . $searchQuery . '%');

                if ($isIdSearch) {
                    $q->orWhere('id', $searchQuery);
                }
            });
        }

        $users = $query->get();

        return response()->json($users);
    }

    //ارشفة
public function destroy($id)
{
    $user = User::findOrFail($id);

    // إذا كان ولي أمر، نحتاج لأرشفة الطلاب المرتبطين به
    if ($user->role === 'ولي أمر') {
        $parent = $user->parent;

        if ($parent) {
            // أرشفة جميع الطلاب التابعين له
            foreach ($parent->students as $student) {
                $student->delete(); // soft delete
            }

            // أرشفة سجل parent
            $parent->delete();
        }
    }

    // أرشفة المستخدم نفسه
    $user->delete();

    return redirect()->route('users.index')->with('success', 'تمت أرشفة المستخدم وطلابه بنجاح.');
}

//استعادة
public function restore($id)
{
    $user = User::withTrashed()->findOrFail($id);

    if ($user->role === 'ولي أمر') {
        $parent = $user->parent()->withTrashed()->first();

        if ($parent) {
            // استرجاع سجل parent
            $parent->restore();

            // استرجاع الطلاب المرتبطين به
            foreach ($parent->students()->withTrashed()->get() as $student) {
                $student->restore();
            }
        }
    }

    $user->restore();

    return redirect()->route('users.index')->with('success', 'تم استعادة المستخدم وطلابه بنجاح.');
}

public function update(Request $request, User $user)
{
    $request->validate([
        'username' => 'required|max:255|unique:users,username,' . $user->id,
        'email' => 'required|email|unique:users,email,' . $user->id,
        'full_name' => 'required|string|max:255',
        'role' => 'required|in:مسؤول,موظف,ولي أمر',
        'phone_number' => 'nullable|string|max:20',
        'current_password' => 'nullable|string',
        'new_password' => 'nullable|string|min:6|confirmed',
    ]);

    // تحديث البيانات العامة
    $user->update([
        'username' => $request->username,
        'email' => $request->email,
        'full_name' => $request->full_name,
        'role' => $request->role,
        'phone_number' => $request->phone_number,
    ]);

    // تحديث كلمة المرور (إذا تم إدخال الجديدة)
    if ($request->filled('new_password')) {
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
    }

    return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
}

}

