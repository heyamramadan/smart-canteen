<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderApiController extends Controller
{
     // جلب مشتريات الطلاب التابعين لولي الأمر
    public function getStudentOrders(Request $request)
    {
        $parent = $request->user();

        if (!$parent) {
            return response()->json(['message' => 'ولي الأمر غير موجود'], 404);
        }

        $orders = Order::with(['orderItems.product', 'student'])
            ->whereIn('student_id', $parent->students->pluck('student_id'))// فقط الطلبات المكتملة
            ->latest()
            ->get();

        return response()->json([
            'message' => 'تم جلب الطلبات بنجاح',
            'orders' => $orders
        ]);
    }
}
