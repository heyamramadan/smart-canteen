<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المنتجات - لوحة تحكم المقصف</title>
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

            <!-- إدارة المستخدمين -->
            <li class="relative">
                <div class="p-3 hover:bg-primary-500 rounded-lg cursor-pointer flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="ml-2">👥</span>
                        <span>إدارة المستخدمين</span>
                    </div>
                </div>
            </li>

            <!-- قسم إدارة المنتجات (محدد) -->
            <li class="p-3 bg-primary-500 rounded-lg transition">
                <a href="#" class="flex items-center">
                    <span class="ml-2">🛒</span>
                    إدارة المنتجات
                </a>
            </li>

            <!-- قسم إدارة التصنيفات -->
            <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                <a href="#" class="flex items-center">
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

    <!-- محتوى إدارة المنتجات -->
    <div class="flex-1 p-6 overflow-auto">
        <!-- شريط البحث وإضافة منتج -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <span class="ml-2">🛒</span>
                إدارة المنتجات
            </h2>

            <div class="flex items-center space-x-4 space-x-reverse">
                <!-- حقل البحث -->
                <div class="relative">
                    <input type="text" placeholder="ابحث عن منتج..."
                           class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
                </div>

                <!-- زر إضافة منتج -->
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                    <span class="ml-1">+</span>
                    إضافة منتج جديد
                </button>
            </div>
        </div>

        <!-- جدول المنتجات -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-right text-sm text-gray-500">الصورة</th>
                            <th class="p-3 text-right text-sm text-gray-500">اسم المنتج</th>
                            <th class="p-3 text-right text-sm text-gray-500">التصنيف</th>
                            <th class="p-3 text-right text-sm text-gray-500">السعر</th>
                            <th class="p-3 text-right text-sm text-gray-500">الكمية</th>
                            <th class="p-3 text-right text-sm text-gray-500">الحالة</th>
                            <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- منتج 1 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-md overflow-hidden">
                                    <img src="https://via.placeholder.com/40" alt="صورة المنتج" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="p-3 text-sm font-medium">ساندويتش جبنة</td>
                            <td class="p-3 text-sm">وجبات سريعة</td>
                            <td class="p-3 text-sm">5.00 ر.س</td>
                            <td class="p-3 text-sm">25</td>
                            <td class="p-3">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">متوفر</span>
                            </td>
                            <td class="p-3 flex items-center">
                                <button class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">
                                    ✏️ تعديل
                                </button>
                                <button class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">
                                    🗑️ حذف
                                </button>
                            </td>
                        </tr>

                        <!-- منتج 2 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-md overflow-hidden">
                                    <img src="https://via.placeholder.com/40" alt="صورة المنتج" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="p-3 text-sm font-medium">عصير برتقال</td>
                            <td class="p-3 text-sm">مشروبات</td>
                            <td class="p-3 text-sm">3.50 ر.س</td>
                            <td class="p-3 text-sm">15</td>
                            <td class="p-3">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">متوفر</span>
                            </td>
                            <td class="p-3 flex items-center">
                                <button class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">
                                    ✏️ تعديل
                                </button>
                                <button class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">
                                    🗑️ حذف
                                </button>
                            </td>
                        </tr>

                        <!-- منتج 3 -->
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-md overflow-hidden">
                                    <img src="https://via.placeholder.com/40" alt="صورة المنتج" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="p-3 text-sm font-medium">كيك شوكولاتة</td>
                            <td class="p-3 text-sm">حلويات</td>
                            <td class="p-3 text-sm">7.00 ر.س</td>
                            <td class="p-3 text-sm">0</td>
                            <td class="p-3">
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs">غير متوفر</span>
                            </td>
                            <td class="p-3 flex items-center">
                                <button class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">
                                    ✏️ تعديل
                                </button>
                                <button class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">
                                    🗑️ حذف
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- تذييل الجدول (ترقيم الصفحات) -->
            <div class="p-4 border-t flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    عرض 1 إلى 3 من 10 منتجات
                </div>
                <div class="flex space-x-2 space-x-reverse">
                    <button class="px-3 py-1 border rounded-md text-sm hover:bg-gray-100">السابق</button>
                    <button class="px-3 py-1 border rounded-md bg-primary-500 text-white text-sm">1</button>
                    <button class="px-3 py-1 border rounded-md text-sm hover:bg-gray-100">2</button>
                    <button class="px-3 py-1 border rounded-md text-sm hover:bg-gray-100">3</button>
                    <button class="px-3 py-1 border rounded-md text-sm hover:bg-gray-100">التالي</button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
