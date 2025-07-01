<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام المقصف - تقارير المبيعات</title>
    <!-- تضمين تيلويند -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- تضمين أيقونات -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-item:hover {
            background-color: #f3f4f6;
            border-right: 4px solid #3b82f6;
        }
        .sidebar-item.active {
            background-color: #eff6ff;
            border-right: 4px solid #3b82f6;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- الشريط الجانبي -->
        <div class="w-64 bg-white shadow-md">
            <div class="p-4 border-b">
                <h2 class="text-xl font-bold text-gray-800 text-center">نظام المقصف</h2>
            </div>
            <nav class="mt-4">
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-home mr-2"></i> لوحة التحكم
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-utensils mr-2"></i> إدارة المقصف
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-user mr-2"></i> العقد الشخصي
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-users mr-2"></i> إدارة المستخدمين
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item active">
                    <i class="fas fa-user-graduate mr-2"></i> إدارة الطالب
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-box mr-2"></i> إدارة المنتجات
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-tags mr-2"></i> إدارة التصنيفات
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-money-bill-wave mr-2"></i> شحن الرصيد
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-id-card mr-2"></i> إصدار بطاقة إلكترونية
                </a>
                <a href="#" class="block py-3 px-6 text-gray-700 hover:bg-gray-100 sidebar-item">
                    <i class="fas fa-file-alt mr-2"></i> التقارير
                </a>
            </nav>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="flex-1 overflow-y-auto">
            <!-- شريط العنوان -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center p-4">
                    <h1 class="text-xl font-bold text-gray-800">إعداد التقارير</h1>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="بحث..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="flex items-center space-x-2">
                            <img src="https://via.placeholder.com/40" alt="صورة المستخدم" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium">مسؤول النظام</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- محتوى التقرير -->
            <main class="p-6">
                <!-- فلترة التقرير -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">فلترة التقرير</h2>
                    <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نوع التقرير</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>تقرير المبيعات</option>
                                <option>تقرير المنتجات</option>
                                <option>تقرير الطلاب</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-filter mr-2"></i> تصفية
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ملخص التقرير -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
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
                            <span class="text-sm text-green-600"><i class="fas fa-arrow-up mr-1"></i> 12% عن الشهر الماضي</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
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
                            <span class="text-sm text-green-600"><i class="fas fa-arrow-up mr-1"></i> 8% عن الشهر الماضي</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6">
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
                            <span class="text-sm text-red-600"><i class="fas fa-arrow-down mr-1"></i> 3% عن الشهر الماضي</span>
                        </div>
                    </div>
                </div>

                <!-- جدول التقرير -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">تفاصيل المبيعات</h3>
                        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            <i class="fas fa-download mr-1"></i> تصدير
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
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-001</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">أحمد محمد</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">ساندويتش جبن</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 10:30</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-002</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">خالد عبدالله</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">عصير برتقال</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3.50 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3.50 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 11:15</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-003</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">سارة علي</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">شوكولاتة</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 12:45</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-004</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">فاطمة حسن</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">ماء</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 14:20</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#ORD-2023-005</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">عبدالرحمن سعيد</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">ساندويتش دجاج</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">6.00 ر.س</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2023-06-21 15:10</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            عرض 1 إلى 5 من 142 طلب
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                السابق
                            </button>
                            <button class="px-3 py-1 border rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                1
                            </button>
                            <button class="px-3 py-1 border rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                2
                            </button>
                            <button class="px-3 py-1 border rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                3
                            </button>
                            <button class="px-3 py-1 border rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                التالي
                            </button>
                        </div>
                    </div>
                </div>

                <!-- المخزون المتبقي -->
                <div class="bg-white rounded-lg shadow-md mt-6 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">المخزون المتبقي</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">ساندويتش جبن</span>
                                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">12 متبقي</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-blue-500 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">ساندويتش دجاج</span>
                                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full">8 متبقي</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-green-500 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">عصير برتقال</span>
                                <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">5 متبقي</span>
                            </div>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full">
                                <div class="h-2 bg-yellow-500 rounded-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div class="border rounded-lg p-4">
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
        // يمكنك إضافة أي JavaScript مخصص هنا
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
        });
    </script>
</body>
</html>
