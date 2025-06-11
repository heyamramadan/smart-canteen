<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلبات الحالية - لوحة التحكم</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        body { font-family: 'Tajawal', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- الشريط الجانبي -->
        <div class="w-64 bg-white shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-orange-600">لوحة التحكم</h2>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li><a href="#" class="block p-2 rounded hover:bg-orange-50 text-gray-700">الرئيسية</a></li>
                    <li><a href="{{ route('current-orders') }}" class="block p-2 rounded bg-orange-100 text-orange-600 font-medium">الطلبات الحالية</a></li>
                    <li><a href="#" class="block p-2 rounded hover:bg-orange-50 text-gray-700">إدارة المنتجات</a></li>
                    <li><a href="#" class="block p-2 rounded hover:bg-orange-50 text-gray-700">التقارير</a></li>
                </ul>
            </nav>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- عنوان الصفحة -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <h1 class="text-xl font-bold text-gray-800">الطلبات الحالية</h1>
            </div>

            <!-- بطاقة الطلب -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- عنوان الطلب -->
                <div class="p-4 border-b border-gray-200">
                    <h2 class="font-bold text-lg"># Current Order</h2>
                    <p class="text-gray-600">- <span class="font-semibold">Client All</span></p>
                </div>

                <!-- جدول الأصناف -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-right border">Name</th>
                                <th class="px-4 py-2 text-right border">Time</th>
                                <th class="px-4 py-2 text-right border">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 border">Lunch items</td>
                                <td class="px-4 py-2 border">Salad items</td>
                                <td class="px-4 py-2 border">Burger items</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border">Coffee items</td>
                                <td class="px-4 py-2 border">Dessert items</td>
                                <td class="px-4 py-2 border">Category Name Category Name Items</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- تفاصيل الطلب -->
                <div class="overflow-x-auto mt-4">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-right border">Name</th>
                                <th class="px-4 py-2 text-right border">Time</th>
                                <th class="px-4 py-2 text-right border">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 border">T-Bone Stake</td>
                                <td class="px-4 py-2 border">2</td>
                                <td class="px-4 py-2 border">$66.00</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border">Soup of the Day</td>
                                <td class="px-4 py-2 border">1</td>
                                <td class="px-4 py-2 border">$7.50</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 border">Pancakes</td>
                                <td class="px-4 py-2 border">2</td>
                                <td class="px-4 py-2 border">$27.00</td>
                            </tr>
                            <tr class="font-semibold bg-gray-50">
                                <td colspan="2" class="px-4 py-2 border">Subtotal Discounts Tax(12%)</td>
                                <td class="px-4 py-2 border">$100.50 -$8.00 $11.20</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- المجموع الكلي -->
                <div class="p-4 bg-gray-50 text-center">
                    <p class="font-bold text-lg mb-1">Total</p>
                    <p class="text-xl font-bold text-orange-600">$93.46</p>
                </div>

                <!-- الوقت -->
                <div class="p-4 text-center text-gray-500 text-sm">
                    <p>209 AM</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
