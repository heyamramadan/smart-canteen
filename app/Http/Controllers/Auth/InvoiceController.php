<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    // عرض صفحة الفواتير
   public function index(Request $request)
{
    $search = $request->input('search');

    $invoices = Order::with(['student', 'employee', 'orderItems.product'])
        ->completed()
        ->when($search, function ($query) use ($search) {
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(perPage: 10);

    return view('invoices', compact('invoices'));
}


    // عرض تفاصيل فاتورة معينة
    public function show($id)
    {
        $invoice = Order::with(['student', 'employee', 'orderItems.product'])
                        ->completed()
                        ->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    // حذف فاتورة
    public function destroy($id)
    {
        $invoice = Order::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')
                         ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    // طباعة الفاتورة
    public function print($id)
    {
        $invoice = Order::with(['student', 'employee', 'orderItems.product'])
                        ->completed()
                        ->findOrFail($id);

        return view('invoices.print', compact('invoice'));
    }
}
