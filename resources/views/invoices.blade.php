
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>قائمة الفواتير - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            100: '#FFEDD5',
                            500: '#F97316',
                            600: '#EA580C',
                            700: '#C2410C',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        body {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
    @include('layouts.sidebar')

    <div class="flex-1 p-6 overflow-auto">
        <!-- رأس الصفحة -->
        <div class="bg-white rounded-xl shadow-lg mb-6 p-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <span class="ml-2">🧾</span>
                قائمة الفواتير
            </h2>

            <form method="GET" action="{{ route('invoices.index') }}" class="relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="ابحث عن طالب..."
                    class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                />
                <button type="submit" class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">🔍</button>
            </form>
        </div>

        <!-- جدول الفواتير -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-sm text-gray-500">رقم الفاتورة</th>
                            <th class="p-3 text-sm text-gray-500">اسم الطالب</th>
                            <th class="p-3 text-sm text-gray-500">اسم الأب</th>
                            <th class="p-3 text-sm text-gray-500">الموظف</th>
                             <th class="p-3 text-sm text-gray-500">الفصل</th>
                            <th class="p-3 text-sm text-gray-500">التاريخ</th>
                            <th class="p-3 text-sm text-gray-500">الإجمالي</th>
                            <th class="p-3 text-sm text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 text-sm font-medium">#{{ $invoice->order_id }}</td>
                           <td class="p-3 text-sm">{{ $invoice->student->full_name ?? '—' }}</td>
                        <td class="p-3 text-sm">{{ $invoice->student->father_name ?? '—' }}</td>
                        <td class="p-3 text-sm">{{ $invoice->employee->full_name ?? '—' }}</td>
                        <td class="p-3 text-sm">{{ $invoice->student->class ?? '—' }}</td>


                            <td class="p-3 text-sm">{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td class="p-3 text-sm font-bold text-primary-700">{{ number_format($invoice->total_amount, 2) }} د.ل</td>
<td class="p-3 flex items-center gap-2">
    <button
        onclick='openInvoiceModal(@json($invoice))'
        class="flex items-center gap-1 border border-primary-500 text-primary-600 bg-white hover:bg-primary-600 hover:text-white px-3 py-1 rounded-full text-sm transition"
        title="عرض الفاتورة"
    >
        👁️ <span>عرض</span>
    </button>

    <button
        onclick='printInvoiceDirect(@json($invoice))'
        class="flex items-center gap-1 border border-primary-500 text-primary-600 bg-white hover:bg-primary-600 hover:text-white px-3 py-1 rounded-full text-sm transition"
        title="طباعة الفاتورة"
    >
        🖨️ <span>طباعة</span>
    </button>
</td>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 text-gray-400">لا توجد فواتير حالياً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- روابط الصفحات -->
            <div class="p-4">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal عرض تفاصيل الفاتورة -->
<div id="invoiceModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
            <h3 class="text-lg font-bold text-primary-700">تفاصيل الفاتورة</h3>

            <button onclick="closeInvoiceModal()" class="text-gray-500 hover:text-gray-700">✖</button>
        </div>
        <div class="p-6 space-y-4" id="invoiceDetails">
            <!-- يتم تعبئة التفاصيل هنا تلقائياً -->
        </div>
    </div>
</div>

<script>
    function openInvoiceModal(invoice) {
        const modal = document.getElementById('invoiceModal');
        const details = document.getElementById('invoiceDetails');

        // بناء محتوى تفاصيل الفاتورة
        let html = `
            <p><strong>رقم الفاتورة:</strong> #${invoice.order_id}</p>
            <p><strong>الطالب:</strong> ${invoice.student?.full_name || '—'}</p>
            <p><strong>اسم الأب:</strong> ${invoice.student?.father_name || '—'}</p>
            <p><strong>الموظف:</strong> ${invoice.employee?.full_name || '—'}</p>

            <p><strong>الفصل:</strong> ${invoice.student?.class || '—'}</p>

            <p><strong>التاريخ:</strong> ${new Date(invoice.created_at).toLocaleDateString()}</p>
            <p><strong>الإجمالي:</strong> ${parseFloat(invoice.total_amount).toFixed(2)} د.ل</p>
        `;

        // عرض الأصناف مع الكمية والسعر لكل صنف
        if (invoice.order_items && invoice.order_items.length > 0) {
            html += `<hr><h4 class="text-md font-bold mb-2">الأصناف:</h4><ul class="list-disc pl-5 space-y-1">`;
            invoice.order_items.forEach(item => {
                html += `<li>${item.product?.name || '—'} × ${item.quantity} = ${(item.price * item.quantity).toFixed(2)} د.ل</li>`;
            });
            html += '</ul>';
        }

        details.innerHTML = html;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeInvoiceModal() {
        const modal = document.getElementById('invoiceModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('invoiceDetails').innerHTML = '';
    }
 function printInvoiceDirect(invoice) {
    let html = `
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <title>فاتورة شراء</title>
            <style>
                body {
                    font-family: 'Tajawal', sans-serif;
                    margin: 40px;
                    color: #333;
                    line-height: 1.6;
                }
                .invoice-box {
                    max-width: 800px;
                    margin: auto;
                    padding: 30px;
                    border: 1px solid #eee;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
                }
                h2 {
                    text-align: center;
                    color: #EA580C;
                    margin-bottom: 20px;
                }
                .details {
                    margin-bottom: 20px;
                }
                .details p {
                    margin: 4px 0;
                }
                .items {
                    width: 100%;
                    border-collapse: collapse;
                }
                .items th, .items td {
                    padding: 10px;
                    text-align: right;
                    border-bottom: 1px solid #ddd;
                }
                .items th {
                    background-color: #F97316;
                    color: white;
                }
                .total {
                    text-align: left;
                    font-size: 18px;
                    font-weight: bold;
                    margin-top: 20px;
                    color: #C2410C;
                }
            </style>
        </head>
        <body>
            <div class="invoice-box">
                <h2>🧾 فاتورة شراء</h2>

                <div class="details">
                    <p><strong>رقم الفاتورة:</strong> #${invoice.order_id}</p>
                    <p><strong>الطالب:</strong> ${invoice.student?.full_name || '—'}</p>
                    <p><strong>اسم الأب:</strong> ${invoice.student?.father_name || '—'}</p>
                    <p><strong>الموظف:</strong> ${invoice.employee?.full_name || '—'}</p>

                    <p><strong>الفصل:</strong> ${invoice.student?.class || '—'}</p>
                    <p><strong>التاريخ:</strong> ${new Date(invoice.created_at).toLocaleDateString()}</p>
                </div>

                <table class="items">
                    <thead>
                        <tr>
                            <th>الصنف</th>
                            <th>الكمية</th>
                            <th>السعر الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${invoice.order_items.map(item => `
                            <tr>
                                <td>${item.product?.name || '—'}</td>
                                <td>${item.quantity}</td>
                                <td>${(item.price * item.quantity).toFixed(2)} د.ل</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>

                <p class="total">الإجمالي: ${parseFloat(invoice.total_amount).toFixed(2)} د.ل</p>
            </div>
        </body>
        </html>
    `;

    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

</script>

</body>
</html>

