<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Studentmodel;
use App\Models\User;
use Illuminate\Http\Request;

class ArchivedStudentController extends Controller
{
      public function index()
    {
        $students = Studentmodel::onlyTrashed()->with('user')->paginate(10);
        return view('archived-students', compact('students'));
    }

    public function restore($id)
    {
        $student = Studentmodel::onlyTrashed()->findOrFail($id);
        $parentUser = User::withTrashed()->find($student->user_id);

        if ($parentUser && $parentUser->trashed()) {
            return redirect()->route('archived-students.index')->with('error', 'لا يمكن استعادة الطالب لأن ولي أمره مؤرشف.');
        }

        $student->restore();
        return redirect()->route('archived-students.index')->with('success', 'تم استعادة الطالب بنجاح.');
    }
}
