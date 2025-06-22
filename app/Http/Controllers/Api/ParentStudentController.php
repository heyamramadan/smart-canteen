<?php

namespace App\Http\Controllers\Api;
use App\Models\ParentModel;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ParentStudentController extends Controller
{
      public function getMyStudents(Request $request)
    {
        // المستخدم الحالي (ولي الأمر) من التوكن
        $user = $request->user();

        // إيجاد بيانات ولي الأمر المرتبط بالمستخدم الحالي
        $parent = ParentModel::where('user_id', $user->id)
            ->with('students') // تحميل الطلاب المرتبطين
            ->first();

        if (!$parent) {
            return response()->json([
                'message' => 'لم يتم العثور على بيانات ولي الأمر.'
            ], 404);
        }

        return response()->json([
            'message' => 'تم جلب الطلاب بنجاح',
            'students' => $parent->students
        ]);
    }
}
