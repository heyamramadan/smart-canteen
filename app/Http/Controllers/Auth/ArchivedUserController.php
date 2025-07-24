<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class ArchivedUserController extends Controller
{
 // عرض المستخدمين المؤرشفين
    public function index()
    {
        $archivedUsers = User::onlyTrashed()->paginate(10);
        return view('archived-users', compact('archivedUsers'));
    }

    // استعادة مستخدم مؤرشف
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

            return redirect()->route('archived-users.index')->with('success', 'تم استعادة ولي الأمر وطلابه بنجاح.');
        } else {
            // استعادة المستخدم نفسه فقط
            $user->restore();

            return redirect()->route('archived-users.index')->with('success', 'تم استعادة المستخدم بنجاح.');
        }
    }
    public function searchArchived(Request $request)
{
    $query = $request->input('query');
    $archivedUsers = User::onlyTrashed()
        ->where(function($q) use ($query) {
            $q->where('username', 'LIKE', "%$query%")
              ->orWhere('full_name', 'LIKE', "%$query%")
              ->orWhere('email', 'LIKE', "%$query%")
              ->orWhere('role', 'LIKE', "%$query%");
        })
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($archivedUsers->map(function($user) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role' => $user->role,
            'created_at' => $user->created_at->format('Y-m-d'),
        ];
    }));
}

}
