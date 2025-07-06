<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>نظام المقصف - تقارير المبيعات</title>
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
        .sidebar-item {
            transition: all 0.3s ease;
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
            <!-- شريط العنوان المحدث -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center p-4">
                    <h1 class="text-xl font-bold text-primary-700 flex items-center">
                        <i class="fas fa-file-alt ml-2"></i>
                        تقارير المبيعات
                    </h1>
                    <div class="text-sm text-gray-600">
                        @if(isset($startDate) && isset($endDate))
                        الفترة: {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}
                        @endif
                    </div>
                </div>
            </header>

            <!-- محتوى التقرير المحدث -->
            <main class="p-6">
                <!-- فلترة التقرير المحدثة -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-primary-700 mb-4">فلترة التقرير</h2>
                    <form method="POST" action="{{ route('report.generate') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الفترة الزمنية</label>
                            <select name="timePeriod" id="timePeriod" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="day" {{ request('timePeriod') == 'day' ? 'selected' : '' }}>يوم</option>
                                <option value="week" {{ request('timePeriod') == 'week' ? 'selected' : '' }}>أسبوع</option>
                                <option value="month" {{ request('timePeriod') == 'month' ? 'selected' : '' }}>شهر</option>
                                <option value="custom" {{ request('timePeriod') == 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
                            </select>
                        </div>
                        <div id="customDateRange" class="{{ request('timePeriod') == 'custom' ? 'md:col-span-2 grid grid-cols-2 gap-4' : 'hidden md:col-span-2 grid grid-cols-2 gap-4' }}">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">من تاريخ</label>
                                <input type="date" name="fromDate" id="fromDate" value="{{ request('fromDate', $startDate->format('Y-m-d')) }}" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">إلى تاريخ</label>
                                <input type="date" name="toDate" id="toDate" value="{{ request('toDate', $endDate->format('Y-m-d')) }}" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>
                        <div id="singleDate" class="{{ request('timePeriod') != 'custom' ? '' : 'hidden' }}">
                            <label class="block text-sm text-gray-600 mb-1">التاريخ</label>
                            <input type="date" name="selectedDate" id="selectedDate" value="{{ request('selectedDate', $startDate->format('Y-m-d')) }}" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition">
                                <i class="fas fa-filter ml-2"></i> تصفية
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ملخص التقرير المحدث -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500">إجمالي المبيعات</p>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSales, 2) }} د.ل</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-green-600"><i class="fas fa-info-circle ml-1"></i> إجمالي المبيعات للفترة</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500">عدد الطلبات</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full text-green-600">
                                <i class="fas fa-shopping-cart text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-green-600"><i class="fas fa-info-circle ml-1"></i> إجمالي الطلبات للفترة</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500">المنتجات المباعة</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalItemsSold }}</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                                <i class="fas fa-box-open text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-green-600"><i class="fas fa-info-circle ml-1"></i> إجمالي المنتجات المباعة</span>
                        </div>
                    </div>
                </div>

                <!-- جدول التقرير المحدث -->
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
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الطالب</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المجموع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orderItems as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-{{ $item->order->order_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->order->student->full_name  ?? 'غير معروف' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->price, 2) }} د.ل</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($item->quantity * $item->price, 2) }} د.ل</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">لا توجد بيانات متاحة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            عرض {{ $orderItems->firstItem() ?? 0 }} إلى {{ $orderItems->lastItem() ?? 0 }} من {{ $orderItems->total() }} طلب
                        </div>
                        <div class="flex space-x-2 space-x-reverse">
                            @if($orderItems->previousPageUrl())
                            <a href="{{ $orderItems->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                السابق
                            </a>
                            @endif

                            @foreach(range(1, $orderItems->lastPage()) as $page)
                                <a href="{{ $orderItems->url($page) }}" class="px-3 py-1 border border-gray-300 rounded-lg {{ $orderItems->currentPage() == $page ? 'text-white bg-primary-500 hover:bg-primary-600' : 'text-gray-700 bg-white hover:bg-gray-50' }} transition">
                                    {{ $page }}
                                </a>
                            @endforeach

                            @if($orderItems->nextPageUrl())
                            <a href="{{ $orderItems->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                التالي
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- المخزون المتبقي المحدث -->
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
        document.addEventListener('DOMContentLoaded', function() {
            // تعيين التاريخ الافتراضي
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]').forEach(input => {
                if (!input.value) {
                    input.value = today;
                }
            });

            // تفعيل عناصر القائمة الجانبية
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.addEventListener('click', function() {
                    document.querySelectorAll('.sidebar-item').forEach(i => {
                        i.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });

            // التحكم في عرض حقول التاريخ حسب نوع الفترة
            const timePeriodSelect = document.getElementById('timePeriod');
            const customDateRange = document.getElementById('customDateRange');
            const singleDate = document.getElementById('singleDate');

            function updateDateFields() {
                const period = timePeriodSelect.value;

                if (period === 'custom') {
                    customDateRange.classList.remove('hidden');
                    singleDate.classList.add('hidden');
                } else {
                    customDateRange.classList.add('hidden');
                    singleDate.classList.remove('hidden');

                    // تعيين التاريخ المناسب حسب الفترة المحددة
                    const dateInput = document.getElementById('selectedDate');
                    const today = new Date();

                    if (period === 'day') {
                        dateInput.value = today.toISOString().split('T')[0];
                    } else if (period === 'week') {
                        const firstDay = new Date(today.setDate(today.getDate() - today.getDay()));
                        dateInput.value = firstDay.toISOString().split('T')[0];
                    } else if (period === 'month') {
                        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                        dateInput.value = firstDay.toISOString().split('T')[0];
                    }
                }
            }

            // تحديث الحقول عند تغيير الخيار
            timePeriodSelect.addEventListener('change', updateDateFields);

            // تهيئة الحقول عند التحميل
            updateDateFields();
        });

        function exportCurrentTableData() {
    // جمع البيانات من الجدول مباشرة
    let rows = [];
    document.querySelectorAll('table tbody tr').forEach(row => {
        let rowData = [];
        row.querySelectorAll('td').forEach((cell, index) => {
            // تجاهل الصف الفارغ (إذا وجد)
            if (index < 7) { // عدد الأعمدة 7
                rowData.push(cell.innerText.trim());
            }
        });
        if (rowData.length > 0) {
            rows.push(rowData);
        }
    });

    // إنشاء محتوى CSV
    const headers = ['رقم الطلب', 'الطالب', 'المنتج', 'الكمية', 'السعر', 'المجموع', 'التاريخ'];
    let csvContent = "\uFEFF" + headers.join(',') + '\n'; // \uFEFF لحل مشكلة الترميز في الإكسل

    rows.forEach(row => {
        csvContent += row.join(',') + '\n';
    });

    // تنزيل الملف
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const fileName = 'الطلبات_' + new Date().toLocaleDateString('ar-SA') + '.csv';

    link.href = URL.createObjectURL(blob);
    link.download = fileName;
    link.click();
}
    </script>
</body>
</html>
