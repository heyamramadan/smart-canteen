<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>نظام المقصف - تقرير يومي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        .sidebar-item:hover {
            background-color: #FFEDD5;
            border-right: 4px solid #F97316;
        }
        .sidebar-item.active {
            background-color: #FFEDD5;
            border-right: 4px solid #F97316;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        @include('layouts.sidebar')

        <!-- المحتوى الرئيسي -->
        <div class="flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center p-4">
                    <h1 class="text-xl font-bold text-primary-700 flex items-center">
                        <i class="fas fa-calendar-day ml-2"></i>
                        التقرير اليومي للمبيعات
                    </h1>
                    <div class="text-sm text-gray-600">
                        التاريخ: {{ \Carbon\Carbon::now()->format('Y-m-d') }}
                    </div>
                </div>
            </header>

            <main class="p-6">
                <!-- ملخص التقرير -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <x-report.card title="إجمالي المبيعات" value="{{ number_format($totalSales, 2) }} ر.س" icon="fas fa-money-bill-wave" color="blue"/>
                    <x-report.card title="عدد الطلبات" value="{{ $totalOrders }}" icon="fas fa-shopping-cart" color="green"/>
                    <x-report.card title="المنتجات المباعة" value="{{ $totalItemsSold }}" icon="fas fa-box-open" color="purple"/>
                </div>

                <!-- تفاصيل المبيعات -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-primary-700">تفاصيل المبيعات</h3>
                        <button onclick="exportCurrentTableData()" class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-download ml-1"></i> تصدير
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs text-gray-500">رقم الفاتورة</th>
                                    <th class="px-6 py-3 text-right text-xs text-gray-500">الطالب</th>
                                    <th class="px-6 py-3 text-right text-xs text-gray-500">المنتج</th>
                                    <th class="px-6 py-3 text-right text-xs text-gray-500">الكمية</th>
                                    <th class="px-6 py-3 text-right text-xs text-gray-500">السعر</th>
                                    <th class="px-6 py-3 text-right text-xs text-gray-500">المجموع</th>
                                    <th class="px-6 py-3 text-right text-xs text-gray-500">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orderItems as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-500">#ORD-{{ $item->order->order_id }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->order->student->full_name ?? 'غير معروف' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($item->price, 2) }} د.ل</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ number_format($item->quantity * $item->price, 2) }} د.ل</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">لا توجد بيانات لليوم الحالي</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- المخزون المتبقي -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-primary-700 mb-4">المخزون المتبقي</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach($products as $product)
                            @php
                                $percentage = $product->quantity > 0 ? min(100, ($product->quantity / 50) * 100) : 0;
                                $colorClass = $percentage > 50 ? 'bg-blue-500' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                                $textColor = $percentage > 50 ? 'text-blue-800' : ($percentage > 20 ? 'text-yellow-800' : 'text-red-800');
                                $bgColor = $percentage > 50 ? 'bg-blue-100' : ($percentage > 20 ? 'bg-yellow-100' : 'bg-red-100');
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">{{ $product->name }}</span>
                                    <span class="text-sm {{ $bgColor }} {{ $textColor }} px-2 py-1 rounded-full">{{ $product->quantity }} متبقي</span>
                                </div>
                                <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 {{ $colorClass }} rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function exportCurrentTableData() {
            let rows = [];
            document.querySelectorAll('table tbody tr').forEach(row => {
                let rowData = [];
                row.querySelectorAll('td').forEach((cell, index) => {
                    if (index < 7) rowData.push(cell.innerText.trim());
                });
                if (rowData.length > 0) rows.push(rowData);
            });

            const headers = ['رقم الطلب', 'الطالب', 'المنتج', 'الكمية', 'السعر', 'المجموع', 'التاريخ'];
            let csvContent = "\uFEFF" + headers.join(',') + '\n';
            rows.forEach(row => csvContent += row.join(',') + '\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const fileName = 'تقرير_يومي_' + new Date().toLocaleDateString('ar-SA') + '.csv';
            link.href = URL.createObjectURL(blob);
            link.download = fileName;
            link.click();
        }
    </script>
</body>
</html>
