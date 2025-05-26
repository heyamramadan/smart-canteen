<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use Illuminate\Http\Request;
use App\Models\Studentmodel;

class StudentController extends Controller
{
    // دالة عرض صفحة إضافة طالب جديد
    public function create()
    {
          $parents = \App\Models\ParentModel::with('user')->get();
    return view('user.student', compact('parents'));
    }

    // دالة تخزين بيانات الطالب الجديدة
    public function store(Request $request)
    {
        // تحقق من صحة البيانات (Validation)
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
           'parent_id' => 'required|exists:parents,parent_id'
           // 'birth_date' => 'nullable|date',
        ]);

        // إنشاء الطالب في قاعدة البيانات
        Studentmodel::create($validated);

        // إعادة التوجيه مع رسالة نجاح إلى قائمة الطلاب
        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح!');
    }

    // دالة عرض قائمة الطلاب
    public function index(){

   $students = Studentmodel::all();
    $parents = ParentModel::with('user')->get(); // تأكد من استدعاء موديل ولي الأمر و علاقته

    return view('user.students', compact('students', 'parents'));
}
}
