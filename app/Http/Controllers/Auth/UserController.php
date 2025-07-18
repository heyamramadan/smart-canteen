<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet; // <-- إضافة موديل المحفظة
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * عرض جميع المستخدمين مع إمكانية عرض المؤرشفين.
     */
    public function index()
    {
$users = User::oldest()->paginate(10);

        return view('user.index', compact('users'));
    }

    /**
     * تخزين مستخدم جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'full_name' => 'required|string|max:255',
            'role' => 'required|in:مسؤول,موظف,ولي أمر',
          'phone_number' => 'required|string|max:20',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'full_name' => $request->full_name,
            'role' => $request->role,
            'phone_number' => $request->phone_number,
        ]);

        // ✅ تعديل: إذا كان المستخدم "ولي أمر"، قم بإنشاء محفظة له مباشرة.
        if ($user->role === 'ولي أمر') {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,       // رصيد ابتدائي
                'daily_limit' => 20, // حد يومي افتراضي
            ]);
        }

        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    /**
     * أرشفة (حذف ناعم) للمستخدم.
     */
   public function destroy($id)
{
    $user = User::findOrFail($id);

    if ($user->role === 'ولي أمر') {
        // أرشفة جميع الطلاب التابعين له مباشرة
        foreach ($user->students as $student) {
            $student->delete(); // soft delete
        }

        // أرشفة محفظة ولي الأمر
        if ($user->wallet) {
            $user->wallet->delete(); // soft delete
        }

        // أرشفة المستخدم نفسه
        $user->delete();

        return redirect()->route('users.index')->with('success', 'تم أرشفة ولي الأمر وطلابه بنجاح.');
    } else {
        // أرشفة المستخدم نفسه فقط
        $user->delete();

        return redirect()->route('users.index')->with('success', 'تمت أرشفة المستخدم بنجاح.');
    }
}

public function restore($id)
{
    $user = User::withTrashed()->findOrFail($id);

    if ($user->role === 'ولي أمر') {
        // استعادة الطلاب
        foreach ($user->students()->withTrashed()->get() as $student) {
            $student->restore();
        }

        // استعادة المحفظة
        $wallet = Wallet::withTrashed()->where('user_id', $user->id)->first();
        if ($wallet) {
            $wallet->restore();
        }

        // استعادة المستخدم نفسه
        $user->restore();

        return redirect()->route('users.index')->with('success', 'تم استعادة ولي الأمر وطلابه بنجاح.');
    } else {
        // استعادة المستخدم نفسه فقط
        $user->restore();

        return redirect()->route('users.index')->with('success', 'تم استعادة المستخدم بنجاح.');
    }
}

    /**
     * تحديث بيانات المستخدم.
     */
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $request->validate([
            'username' => 'required|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'full_name' => 'required|string|max:255',
            'role' => 'required|in:مسؤول,موظف,ولي أمر',
           'phone_number' => 'required|string|max:20',

            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        // حفظ الدور القديم للمقارنة
        $oldRole = $user->role;

        // تحديث البيانات العامة
        $user->update($request->only('username', 'email', 'full_name', 'role', 'phone_number'));

        // ✅ تعديل: تحقق إذا تم تغيير الدور إلى "ولي أمر"
        // ولم يكن ولي أمر من قبل، وليس لديه محفظة
        if ($user->role === 'ولي أمر' && $oldRole !== 'ولي أمر' && !$user->wallet) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'daily_limit' => 20,
            ]);
        }

        // تحديث كلمة المرور (إذا تم إدخال الجديدة)
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
            }
            $user->update(['password' => Hash::make($request->new_password)]);
        }

        return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    /**
     * البحث عن مستخدمين.
     * (لا تحتاج هذه الدالة لأي تعديل)
     */
    public function search(Request $request)
    {
        $searchQuery = $request->input('query');
      $query = User::query(); // فقط غير المؤرشفين


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
}
