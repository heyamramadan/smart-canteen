<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم إدارة المقصف</title>
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
    <!-- الشريط الجانبي -->
    <div class="w-64 bg-gradient-to-b from-primary-700 to-primary-600 text-white p-4">
        <h2 class="text-xl font-bold mb-8 text-center pt-4">إدارة المقصف</h2>

        <ul class="space-y-3">
            <!-- الملف الشخصي -->
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="#" class="flex items-center">
                    <span class="ml-2">👤</span>
                    الملف الشخصي
                </a>
            </li>

            <!-- إدارة المستخدمين (بدون قائمة منسدلة) -->
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="{{ url('/index') }}" class="flex items-center">
                    <span class="ml-2">👥</span>
                    إدارة المستخدمين
                </a>
            </li>

            <!-- إدارة الطلاب (مضافة جديدة) -->
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="{{ url('/students') }}" class="flex items-center">
                    <span class="ml-2">🎒</span>
                    إدارة الطلاب
                </a>
            </li>

            <!-- قسم إدارة المنتجات -->
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="{{ url('/products') }}" class="flex items-center">
                    <span class="ml-2">🛒</span>
                    إدارة المنتجات
                </a>
            </li>

            <!-- قسم إدارة التصنيفات -->
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="{{ url('/Categories') }}" class="flex items-center">
                    <span class="ml-2">📂</span>
                    إدارة التصنيفات
                </a>
            </li>

            <!-- قسم البطاقات الإلكترونية -->
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="#" class="flex items-center">
                    <span class="ml-2">💳</span>
                    البطاقات الإلكترونية
                </a>
            </li>

            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="#" class="flex items-center">
                    <span class="ml-2">📈</span>
                    التقارير
                </a>
            </li>
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="#" class="flex items-center">
                    <span class="ml-2">⚙️</span>
                    إعدادات النظام
                </a>
            </li>
        </ul>
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="flex-1 p-6 overflow-auto">
        <!-- بطاقات الإحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-primary-500">
                <h3 class="text-gray-500 text-sm">الطلبات</h3>
                <p class="text-2xl font-bold text-primary-600">120</p>
                <div class="mt-2 text-primary-500 text-xs">↑ 12% عن الشهر الماضي</div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm">أولياء الأمور</h3>
                <p class="text-2xl font-bold text-blue-600">85</p>
                <div class="mt-2 text-blue-500 text-xs">↑ 5% زيادة</div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm">موظفي المقصف</h3>
                <p class="text-2xl font-bold text-green-600">5</p>
                <div class="mt-2 text-green-500 text-xs">+2 موظف جديد</div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-purple-500">
                <h3 class="text-gray-500 text-sm">إجمالي المبيعات</h3>
                <p class="text-2xl font-bold text-purple-600">3.500 ر.س</p>
                <div class="mt-2 text-purple-500 text-xs">↑ 20% نمو</div>
            </div>
        </div>

        <!-- جدول المستخدمين -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-bold text-primary-700">👥 إدارة المستخدمين</h2>
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition">
                    + إضافة مستخدم
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-right text-sm text-gray-500">الحالة</th>
                            <th class="p-3 text-right text-sm text-gray-500">البريد الإلكتروني</th>
                            <th class="p-3 text-right text-sm text-gray-500">الصلاحية</th>
                            <th class="p-3 text-right text-sm text-gray-500">الاسم</th>
                            <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3"><span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">✔️ مفعل</span></td>
                            <td class="p-3 text-sm">mohamed@example.com</td>
                            <td class="p-3 text-sm"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">ولي أمر</span></td>
                            <td class="p-3 text-sm font-medium">أحمد علي</td>
                            <td class="p-3">
                                <button class="text-primary-500 hover:text-primary-700 mr-2">✏️</button>
                                <button class="text-red-500 hover:text-red-700">🗑️</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3"><span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">✔️ مفعل</span></td>
                            <td class="p-3 text-sm">fatima@example.com</td>
                            <td class="p-3 text-sm"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">ولي أمر</span></td>
                            <td class="p-3 text-sm font-medium">فاطمة محمد</td>
                            <td class="p-3">
                                <button class="text-primary-500 hover:text-primary-700 mr-2">✏️</button>
                                <button class="text-red-500 hover:text-red-700">🗑️</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3"><span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs">✖️ غير مفعل</span></td>
                            <td class="p-3 text-sm">sara@example.com</td>
                            <td class="p-3 text-sm"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">موظف مقصف</span></td>
                            <td class="p-3 text-sm font-medium">محمود عبد الله</td>
                            <td class="p-3">
                                <button class="text-primary-500 hover:text-primary-700 mr-2">✏️</button>
                                <button class="text-red-500 hover:text-red-700">🗑️</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- بطاقات التقارير -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold mb-2 text-primary-700">📝 تقرير اليومية</h3>
                        <p class="text-gray-500 text-sm">آخر تحديث: 10 مايو 2023</p>
                    </div>
                    <span class="bg-primary-100 text-primary-800 p-2 rounded-lg">⬇️</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold mb-2 text-blue-700">📊 تقرير المعاملات الشهرية</h3>
                        <p class="text-gray-500 text-sm">آخر تحديث: 10 مايو 2023</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 p-2 rounded-lg">⬇️</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold mb-2 text-green-700">🛒 تقرير المنتجات المباعة</h3>
                        <p class="text-gray-500 text-sm">آخر تحديث: 10 مايو 2023</p>
                    </div>
                    <span class="bg-green-100 text-green-800 p-2 rounded-lg">⬇️</span>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
