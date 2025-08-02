<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class ApiWalletController extends Controller
{
 // عرض بيانات المحفظة (الرصيد + السقف)
public function getStudentsLimits(Request $request)
{
    $parent = $request->user();

    $students = $parent->students()->select('student_id', 'full_name', 'daily_limit')->get();

    if ($students->isEmpty()) {
        return response()->json(['message' => 'لا يوجد طلاب مرتبطين بهذا الحساب.'], 404);
    }

    return response()->json(['students_limits' => $students]);
}

    // تحديث أو إضافة سقف الشراء اليومي
public function updateStudentDailyLimit(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,student_id',
        'daily_limit' => 'required|numeric|min:0',
    ]);

    $parent = $request->user();

    $student = $parent->students()->where('student_id', $request->student_id)->first();

    if (!$student) {
        return response()->json(['message' => 'الطالب غير موجود أو لا يتبع هذا الحساب.'], 404);
    }

    $student->daily_limit = $request->daily_limit;
    $student->save();

    return response()->json([
        'message' => 'تم تحديث سقف الشراء اليومي للطالب بنجاح',
        'student' => [
            'id' => $student->student_id,
            'name' => $student->full_name,
            'daily_limit' => $student->daily_limit
        ],
    ]);
}
// ✅ دالة جديدة: جلب رصيد المحفظة لولي الأمر
public function getWalletBalance(Request $request)
{
    $parent = $request->user();

    $wallet = $parent->wallet; // يتطلب أن يكون لدى User علاقة wallet()

    if (!$wallet) {
        return response()->json(['message' => 'لم يتم العثور على المحفظة'], 404);
    }

    return response()->json([
        'message' => 'تم جلب رصيد المحفظة بنجاح',
        'balance' => $wallet->balance,
    ]);
}

}
