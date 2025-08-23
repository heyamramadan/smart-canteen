<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;

class ParentStudentController extends Controller
{//مسؤولة عن جلب الطلاب المرتبطين بولي الأمر
      public function getMyStudents(Request $request)
    {
        $user = $request->user();

   $parent = User::where('role', 'ولي أمر')->where('id', $user->id)

            ->with('students') 
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
