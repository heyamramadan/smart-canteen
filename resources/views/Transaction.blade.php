<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل المعاملات - المقصف الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        body {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- القائمة الجانبية -->
        <div class="w-64 bg-blue-800 text-white p-4">
            <h1 class="text-2xl font-bold mb-8 text-center">المقصف الذكي</h1>

            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-home ml-2"></i>
                            الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded bg-blue-900">
                            <i class="fas fa-wallet ml-2"></i>
                            سجل المعاملات
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-users ml-2"></i>
                            إدارة المستخدمين
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-shopping-cart ml-2"></i>
                            إدارة الطلبات
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-boxes ml-2"></i>
                            إدارة المنتجات
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-tags ml-2"></i>
                            إدارة التصنيفات
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-credit-card ml-2"></i>
                            شحن المحفظة
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-id-card ml-2"></i>
                            إصدار بطاقة إلكترونية
                        </a>
                    </li>
                    <li>
                        <a href="#" class="block py-2 px-4 rounded hover:bg-blue-700">
                            <i class="fas fa-chart-bar ml-2"></i>
                            التقارير
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="flex-1 p-8 overflow-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">سجل المعاملات</h2>

                    <div class="flex space-x-4 space-x-reverse">
                        <div class="relative">
                            <input type="text" placeholder="بحث..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <select class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>فلتر حسب النوع</option>
                            <option>كل المعاملات</option>
                            <option>إيداع</option>
                            <option>سحب</option>
                        </select>
                        <select class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>فلتر حسب التاريخ</option>
                            <option>اليوم</option>
                            <option>أسبوع</option>
                            <option>شهر</option>
                        </select>
                    </div>
                </div>

                <!-- جدول المعاملات -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="py-3 px-4 text-right">رقم المعاملة</th>
                                <th class="py-3 px-4 text-right">ولي الأمر</th>
                                <th class="py-3 px-4 text-right">الطالب</th>
                                <th class="py-3 px-4 text-right">النوع</th>
                                <th class="py-3 px-4 text-right">المبلغ</th>
                                <th class="py-3 px-4 text-right">الرصيد قبل</th>
                                <th class="py-3 px-4 text-right">الرصيد بعد</th>
                                <th class="py-3 px-4 text-right">التاريخ</th>
                                <th class="py-3 px-4 text-right">الملاحظات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- معاملة إيداع -->
                            <tr>
                                <td class="py-4 px-4">#TRX-1001</td>
                                <td class="py-4 px-4">أحمد محمد</td>
                                <td class="py-4 px-4">محمد أحمد</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">إيداع</span>
                                </td>
                                <td class="py-4 px-4 text-green-600 font-medium">+50.00 ر.س</td>
                                <td class="py-4 px-4">120.00 ر.س</td>
                                <td class="py-4 px-4">170.00 ر.س</td>
                                <td class="py-4 px-4">05/07/2025 10:30 ص</td>
                                <td class="py-4 px-4">شحن محفظة</td>
                            </tr>

                            <!-- معاملة سحب -->
                            <tr>
                                <td class="py-4 px-4">#TRX-1002</td>
                                <td class="py-4 px-4">علي حسن</td>
                                <td class="py-4 px-4">حسن علي</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">سحب</span>
                                </td>
                                <td class="py-4 px-4 text-red-600 font-medium">-15.00 ر.س</td>
                                <td class="py-4 px-4">85.00 ر.س</td>
                                <td class="py-4 px-4">70.00 ر.س</td>
                                <td class="py-4 px-4">05/07/2025 11:45 ص</td>
                                <td class="py-4 px-4">شراء وجبة غداء</td>
                            </tr>

                            <!-- معاملة إيداع -->
                            <tr>
                                <td class="py-4 px-4">#TRX-1003</td>
                                <td class="py-4 px-4">سالم عبدالله</td>
                                <td class="py-4 px-4">عبدالله سالم</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">إيداع</span>
                                </td>
                                <td class="py-4 px-4 text-green-600 font-medium">+100.00 ر.س</td>
                                <td class="py-4 px-4">0.00 ر.س</td>
                                <td class="py-4 px-4">100.00 ر.س</td>
                                <td class="py-4 px-4">04/07/2025 09:15 ص</td>
                                <td class="py-4 px-4">شحن محفظة</td>
                            </tr>

                            <!-- معاملة سحب -->
                            <tr>
                                <td class="py-4 px-4">#TRX-1004</td>
                                <td class="py-4 px-4">أحمد محمد</td>
                                <td class="py-4 px-4">محمد أحمد</td>
                                <td class="py-4 px-4">
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">سحب</span>
                                </td>
                                <td class="py-4 px-4 text-red-600 font-medium">-25.00 ر.س</td>
                                <td class="py-4 px-4">170.00 ر.س</td>
                                <td class="py-4 px-4">145.00 ر.س</td>
                                <td class="py-4 px-4">04/07/2025 02:30 م</td>
                                <td class="py-4 px-4">شراء وجبة إفطار</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- تذييل الجدول -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-gray-600">
                        عرض <span class="font-medium">1</span> إلى <span class="font-medium">4</span> من <span class="font-medium">4</span> معاملات
                    </div>
                    <div class="flex space-x-2 space-x-reverse">
                        <button class="px-4 py-2 border rounded-lg bg-gray-100 text-gray-600 disabled">السابق</button>
                        <button class="px-4 py-2 border rounded-lg bg-blue-600 text-white">1</button>
                        <button class="px-4 py-2 border rounded-lg bg-gray-100 text-gray-600 disabled">التالي</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // يمكنك إضافة JavaScript هنا للتفاعل مع الواجهة
        document.addEventListener('DOMContentLoaded', function() {
            console.log('صفحة سجل المعاملات جاهزة');
        });
    </script>
</body>
</html>
