<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - لوحة تحكم المقصف</title>
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
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        @include('layouts.sidebar')

        <!-- محتوى إدارة المستخدمين -->
        <div class="flex-1 p-6 overflow-auto">
            <!-- شريط البحث وإضافة مستخدم -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-primary-700 flex items-center">
                    <span class="ml-2">👥</span>
                    إدارة المستخدمين
                </h2>

                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- حقل البحث -->
                    <div class="relative">
                        <input type="text" placeholder="ابحث عن مستخدم..."
                               class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
                    </div>

                    <!-- زر إضافة مستخدم -->
                    <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                        <span class="ml-1">+</span> إضافة مستخدم جديد
                    </button>
                </div>
            </div>

            <!-- جدول المستخدمين -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-right text-sm text-gray-500">الاسم</th>
                                <th class="p-3 text-right text-sm text-gray-500">البريد الإلكتروني</th>
                                <th class="p-3 text-right text-sm text-gray-500">الدور</th>
                                <th class="p-3 text-right text-sm text-gray-500">الحالة</th>
                                <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- مستخدم 1 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 text-sm font-medium">أحمد علي</td>
                                <td class="p-3 text-sm">ahmed@example.com</td>
                                <td class="p-3 text-sm">مدير النظام</td>
                                <td class="p-3">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">نشط</span>
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
            </div>
        </div>
    </div>
</body>
</html>
