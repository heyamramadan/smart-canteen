<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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

<!-- الشريط الجانبي ثابت على اليمين -->
<div class="fixed top-0 right-0 h-screen w-64 bg-gradient-to-b from-primary-700 to-primary-600 text-white p-4 overflow-y-auto">
    <h2 class="text-xl font-bold mb-8 text-center pt-4">إدارة المقصف</h2>

    <ul class="space-y-3">
        <!-- الملف الشخصي: يظهر للجميع -->
        <li class="p-3 hover:bg-primary-500 rounded-lg transition">
            <a href="{{ url('/profile') }}" class="flex items-center">
                <span class="ml-2">👤</span>
                الملف الشخصي
            </a>
        </li>

        @auth
            @if(auth()->user()->role === 'مسؤول')
                <!-- صلاحيات المسؤول الكاملة -->
                <li class="p-3 {{ request()->is('index*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/index') }}" class="flex items-center">
                        <span class="ml-2">👥</span>
                        إدارة المستخدمين
                    </a>
                </li>
                <li class="p-3 {{ request()->is('students*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/students') }}" class="flex items-center">
                        <span class="ml-2">🎒</span>
                        إدارة الطلاب
                    </a>
                </li>
                <li class="p-3 {{ request()->is('products*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/products') }}" class="flex items-center">
                        <span class="ml-2">🛒</span>
                        إدارة المنتجات
                    </a>
                </li>
                <li class="p-3 {{ request()->is('categories*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/categories') }}" class="flex items-center">
                        <span class="ml-2">📂</span>
                        إدارة التصنيفات
                    </a>
                </li>
                <li class="p-3 {{ request()->is('wallet*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/wallet') }}" class="flex items-center">
                        <span class="ml-2">💰</span>
                        شحن المحفظة
                    </a>
                </li>
                <li class="p-3 {{ request()->is('cards*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/cards') }}" class="flex items-center">
                        <span class="ml-2">💳</span>
                        إصدار بطاقة إلكترونية
                    </a>
                </li>
            @endif

            <!-- المبيعات: تظهر للمسؤول والموظف -->
            <li class="p-3 {{ request()->is('point*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                <a href="{{ url('/point') }}" class="flex items-center">
                    <span class="ml-2">🧾</span>
                    المبيعات
                </a>
            </li>
            <li class="p-3 {{ request()->is('invoices*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                <a href="{{ url('/invoices') }}" class="flex items-center">
                    <span class="ml-2">📄</span>
                    الفواتير
                </a>
            </li>

            @if(auth()->user()->role === 'مسؤول')
                <!-- التقارير تظهر للمسؤول فقط -->
                <li class="p-3 {{ request()->is('reports*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/reports') }}" class="flex items-center">
                        <span class="ml-2">📊</span>
                        التقارير
                    </a>
                </li>
            @endif

            <li class="p-3 hover:bg-primary-500 rounded-lg transition cursor-pointer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full text-white text-right">
                        <span class="ml-2">🚪</span>
                        تسجيل خروج
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</div>

<!-- المحتوى الرئيسي مع تعويض هامش يمين الشريط الجانبي -->
<div class="mr-64 p-6 overflow-auto min-h-screen bg-gray-50">
    @if(auth()->user()->role === 'مسؤول')
        <!-- بطاقات الإحصائيات للمسؤول -->
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

        <!-- جدول المستخدمين (للمسؤول فقط) -->
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
    @endif

    <!-- قسم المبيعات (للمسؤول والموظف) -->
    <div class="bg-white rounded-xl shadow-lg mb-8">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary-700">🧾 المبيعات</h2>
            @if(auth()->user()->role === 'مسؤول')
                <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition">
                    + إضافة عملية بيع
                </button>
            @endif
        </div>
        <div class="p-4">
            <!-- محتوى قسم المبيعات هنا -->
            <p class="text-gray-600">هنا سيتم عرض سجل المبيعات والعمليات اليومية</p>
            <!-- يمكنك إضافة جدول المبيعات أو أي محتوى آخر هنا -->
        </div>
    </div>

    @if(auth()->user()->role === 'مسؤول')
        <!-- بطاقات التقارير (للمسؤول فقط) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-8">
            <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold mb-2 text-primary-700">📝 تقرير اليومية</h3>
                        <p class="text-gray-500 text-sm">آخر تحديث: {{ now()->format('d M Y') }}</p>
                    </div>
                    <span class="bg-primary-100 text-primary-800 p-2 rounded-lg">⬇️</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold mb-2 text-blue-700">📊 تقرير المعاملات الشهرية</h3>
                        <p class="text-gray-500 text-sm">آخر تحديث: {{ now()->format('d M Y') }}</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 p-2 rounded-lg">⬇️</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold mb-2 text-green-700">🛒 تقرير المنتجات المباعة</h3>
                        <p class="text-gray-500 text-sm">آخر تحديث: {{ now()->format('d M Y') }}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 p-2 rounded-lg">⬇️</span>
                </div>
            </div>
        </div>
    @endif
</div>

</body>
</html>
