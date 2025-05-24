<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\studentmodel;
use App\Models\ParentModel;

class StudentController extends Controller
{
    // عرض جميع الطلاب
    public function index()
    {
        $students = studentmodel::with('parent')->get();
        return view('students.index', compact('students'));
    }

    // عرض نموذج إنشاء طالب جديد
    public function create()
    {
        $parents = ParentModel::all(); // لعرض أولياء الأمور في القائمة المنسدلة
        return view('students.create', compact('parents'));
    }

    // حفظ الطالب الجديد
    public function store(Request $request)
    {
        $request->validate([
            'parent_id'   => 'required|exists:parents,parent_id',
            'full_name'   => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'class'       => 'required|string|max:255',
        ]);

        studentmodel::create($request->all());

        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح');
    }

    // عرض تفاصيل طالب معين
    public function show($id)
    {
        $student = studentmodel::with('parent')->findOrFail($id);
        return view('students.show', compact('student'));
    }

    // عرض نموذج التعديل
    public function edit($id)
    {
        $student = studentmodel::findOrFail($id);
        $parents = ParentModel::all();
        return view('students.edit', compact('student', 'parents'));
    }

    // تحديث بيانات الطالب
    public function update(Request $request, $id)
    {
        $request->validate([
            'parent_id'   => 'required|exists:parents,parent_id',
            'full_name'   => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'class'       => 'required|string|max:255',
        ]);

        $student = studentmodel::findOrFail($id);
        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب');
    }

    // حذف الطالب
    public function destroy($id)
    {
        $student = studentmodel::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'تم حذف الطالب');
    }
}
