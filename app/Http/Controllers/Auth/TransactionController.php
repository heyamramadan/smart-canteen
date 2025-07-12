<?php

namespace App\Http\Controllers\Auth;

use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * عرض سجل المعاملات مع الفلاتر.
     */
    public function index(Request $request)
    {
        // ✅ تعديل: تحميل العلاقات المباشرة (المستخدم وطلاب المستخدم)
        $query = WalletTransaction::with(relations: ['wallet.user.students']);

        // 🔍 البحث باسم ولي الأمر أو الطالب
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // ✅ تعديل: البحث في اسم المستخدم (ولي الأمر) مباشرة
                $q->whereHas('wallet.user', function ($userQuery) use ($search) {
                    $userQuery->where('full_name', 'like', "%{$search}%");
                })
                // ✅ تعديل: البحث في أسماء الطلاب المرتبطين بولي الأمر
                ->orWhereHas('wallet.user.students', function ($studentQuery) use ($search) {
                    $studentQuery->where('full_name', 'like', "%{$search}%");
                });
            });
        }

        // 🔍 فلترة حسب النوع (لا تغيير هنا)
        if ($request->filled('type') && in_array($request->type, ['إيداع', 'سحب'])) {
            $query->where('type', $request->type);
        }

        // 🔍 فلترة حسب التاريخ (لا تغيير هنا)
        if ($request->filled('day') || $request->filled('month') || $request->filled('year')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('day')) $q->whereDay('created_at', $request->day);
                if ($request->filled('month')) $q->whereMonth('created_at', $request->month);
                if ($request->filled('year')) $q->whereYear('created_at', $request->year);
            });
        }

        $transactions = $query->latest('created_at')->paginate(10);

        return view('Transaction', compact('transactions'));
    }

    /**
     * دالة البحث عبر AJAX.
     */
    public function search(Request $request)
    {
        // ✅ تعديل: تحديث العلاقات المحملة
        $query = WalletTransaction::with(['wallet.user.students']);

        // تطبيق الفلاتر بنفس منطق دالة index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('wallet.user', function ($userQuery) use ($search) {
                    $userQuery->where('full_name', 'like', "%{$search}%");
                })->orWhereHas('wallet.user.students', function ($studentQuery) use ($search) {
                    $studentQuery->where('full_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('type') && in_array($request->type, ['إيداع', 'سحب'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('day') || $request->filled('month') || $request->filled('year')) {
            $query->where(function ($q) use ($request) {
                if ($request->filled('day')) $q->whereDay('created_at', $request->day);
                if ($request->filled('month')) $q->whereMonth('created_at', $request->month);
                if ($request->filled('year')) $q->whereYear('created_at', $request->year);
            });
        }

        $transactions = $query->latest('created_at')->take(20)->get();

        // ✅ تعديل: تحديث طريقة الوصول للبيانات لتناسب العلاقات الجديدة
        return response()->json($transactions->map(function ($tx) {
            $user = $tx->wallet->user ?? null;
            $students = $user ? $user->students : collect();

            // منطق حساب الرصيد قبل العملية (لا تغيير هنا)
            $balanceBefore = $tx->wallet->balance;
            if ($tx->type === 'إيداع') {
                $balanceBefore -= $tx->amount;
            } else {
                $balanceBefore += abs($tx->amount); // استخدام القيمة المطلقة للتأكد من الجمع الصحيح
            }

            return [
                'id' => $tx->transaction_id, // استخدام المفتاح الأساسي للجدول
                'amount' => number_format(abs($tx->amount), 2), // عرض القيمة موجبة دائماً
                'type' => $tx->type,
                'created_at' => $tx->created_at->format('d/m/Y h:i A'),
                'parent_name' => $user->full_name ?? 'ولي أمر محذوف',
                'student_names' => $students->pluck('full_name')->implode(', ') ?: 'لا يوجد طلاب',
                'balance_before' => number_format($balanceBefore, 2),
                'balance_after' => number_format($tx->wallet->balance, 2),
                'reference' => $tx->reference,
            ];
        }));
    }
}
