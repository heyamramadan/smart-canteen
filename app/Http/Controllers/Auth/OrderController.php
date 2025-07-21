<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StudentModel;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // <-- إضافة Carbon للتعامل مع التواريخ

class OrderController extends Controller
{
    public function create()
    {
        // عرض صفحة واجهة البيع (لا تغيير هنا)
        return view('Point of Sale');
    }

    public function store(Request $request)
    {
        // التحقق من صلاحيات المستخدم (مسؤول أو موظف)
        if (!Auth::check() || !in_array(Auth::user()->role, ['مسؤول', 'موظف'])) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك بإتمام هذه العملية'], 403);
        }
    
        Log::info('==== بدء عملية البيع ====');
        Log::info('بيانات الطلب الواردة:', $request->all());
    
        $validated = $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'pin_code' => 'required|string',
        ]);
    
        // جلب بيانات الطالب مع PIN
        $student = StudentModel::findOrFail($validated['student_id']);
    
        Log::info('==== بيانات الرقم السري ====');
        Log::info('الرقم السري المخزن في قاعدة البيانات:', [
            'value' => $student->pin_code,
            'type' => gettype($student->pin_code),
            'length' => strlen($student->pin_code),
            'trimmed' => trim(strval($student->pin_code)),
            'trimmed_length' => strlen(trim(strval($student->pin_code))),
        ]);
    
        Log::info('الرقم السري المرسل من العميل:', [
            'value' => $validated['pin_code'],
            'type' => gettype($validated['pin_code']),
            'length' => strlen($validated['pin_code']),
            'trimmed' => trim(strval($validated['pin_code'])),
            'trimmed_length' => strlen(trim(strval($validated['pin_code']))),
        ]);
    
        // التحقق من كلمة السر
        $storedPin = trim(strval($student->pin_code));
        $receivedPin = trim(strval($validated['pin_code']));
    
        Log::info('==== نتائج المقارنة ====');
        Log::info('المقارنة بدون trim:', [
            'result' => $student->pin_code === $validated['pin_code'] ? 'متطابق' : 'غير متطابق'
        ]);
        Log::info('المقارنة مع trim:', [
            'result' => $storedPin === $receivedPin ? 'متطابق' : 'غير متطابق'
        ]);
        Log::info('المقارنة مع ==:', [
            'result' => $storedPin == $receivedPin ? 'متطابق' : 'غير متطابق'
        ]);
    
        if ($storedPin !== $receivedPin) {
            Log::error('فشل التحقق من الرقم السري', [
                'stored_pin' => $storedPin,
                'received_pin' => $receivedPin,
                'comparison' => '!=='
            ]);
            return response()->json([
                'success' => false, 
                'message' => 'الرقم السري غير صحيح'
            ], 401);
        }
    
        Log::info('تم التحقق من الرقم السري بنجاح');
    
        try {
            // استخدام DB::transaction لضمان تنفيذ كل العمليات أو التراجع عنها كلها
            $orderResponse = DB::transaction(function () use ($validated) {
                // ... بقية الكود كما هو ...
            });
    
            Log::info('تمت عملية البيع بنجاح', ['order_id' => $orderResponse['order_id'] ?? null]);
            return response()->json($orderResponse);
    
        } catch (\Exception $e) {
            Log::error('حدث خطأ أثناء عملية البيع:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
