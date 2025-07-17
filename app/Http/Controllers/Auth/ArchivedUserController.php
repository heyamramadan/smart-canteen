<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        $user->restore();

        return redirect()->route('archived-users.index')->with('success', 'تمت استعادة المستخدم بنجاح.');
    }}
