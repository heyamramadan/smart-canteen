<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>نظام المقصف - تقارير المبيعات</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

                </div>
            </header>

            <!-- محتوى التقرير المحدث -->
            <main class="p-6">
                <!-- فلترة التقرير المحدثة -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-primary-700 mb-4">فلترة التقرير</h2>
                    <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الفترة الزمنية</label>
                            <select id="timePeriod" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="day">يوم</option>
                                <option value="week">أسبوع</option>
                                <option value="month">شهر</option>
                                <option value="custom">فترة مخصصة</option>
                            </select>
                        </div>
                        <div id="customDateRange" class="hidden md:col-span-2 grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">من تاريخ</label>
                                <input type="date" id="fromDate" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">إلى تاريخ</label>
                                <input type="date" id="toDate" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>
                        <div id="singleDate" class="hidden">
                            <label class="block text-sm text-gray-600 mb-1">تاريخ اليوم</label>
                            <input type="date" id="selectedDate" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
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
                                <p class="text-2xl font-bold text-gray-800">5,420 ر.س</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-green-600"><i class="fas fa-arrow-up ml-1"></i> 12% عن الشهر الماضي</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500">عدد الطلبات</p>
                                <p class="text-2xl font-bold text-gray-800">142</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full text-green-600">
                                <i class="fas fa-shopping-cart text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-green-600"><i class="fas fa-arrow-up ml-1"></i> 8% عن الشهر الماضي</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500">المنتجات المباعة</p>
                                <p class="text-2xl font-bold text-gray-800">856</p>
                            </div>
                            <div class="p-3 bg-purple-100 rounded-full text-purple-600">
                                <i class="fas fa-box-open text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-red-600"><i class="fas fa-arrow-down ml-1"></i> 3% عن الشهر الماضي</span>
                        </div>
                    </div>
                </div>

                <!-- جدول التقرير المحدث -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-primary-700">تفاصيل المبيعات</h3>
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            <i class="fas fa-download ml-1"></i> تصدير
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الطالب</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتج</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المجموع</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">أحمد محمد</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">ساندويتش جبن</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 10:30</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">خالد عبدالله</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">عصير برتقال</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3.50 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3.50 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 11:15</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">سارة علي</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">شوكولاتة</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 12:45</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            عرض 1 إلى 5 من 142 طلب
                        </div>
                        <div class="flex space-x-2 space-x-reverse">
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                السابق
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-white bg-primary-500 hover:bg-primary-600 transition">
                                1
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                2
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                3
                            </button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                التالي
                            </button>
                        </div>
                    </div>
                </div>

                <!-- المخزون المتبقي المحدث -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-primary-700 mb-4">المخزون المتبقي</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">ساندويتش جبن</span>
                                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">12 متبقي</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-blue-500 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">ساندويتش دجاج</span>
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">8 متبقي</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-green-500 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">عصير برتقال</span>
                                <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">5 متبقي</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-yellow-500 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">شوكولاتة</span>
                                <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded-full">3 متبقي</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-red-500 rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
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

            // تعيين التواريخ الافتراضية للحقول المخصصة
            const fromDate = document.getElementById('fromDate');
            const toDate = document.getElementById('toDate');
            const selectedDate = document.getElementById('selectedDate');

            if (!fromDate.value) fromDate.value = today;
            if (!toDate.value) toDate.value = today;
            if (!selectedDate.value) selectedDate.value = today;
        });
    </script>
</body>
</html>
