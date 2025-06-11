<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>الطلبات الحالية - لوحة تحكم المقصف</title>
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

        <div class="flex-1 p-6 overflow-auto">
            <div class="bg-white rounded-xl shadow-lg mb-6 p-4">
                <h2 class="text-lg font-bold text-primary-700 flex items-center">
                    <span class="ml-2">🛒</span> الطلبات الحالية
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-md font-semibold text-gray-700">قائمة الطلبات</h3>
                    <div class="flex items-center gap-2">
                        <select id="clientFilter" class="border rounded-lg px-3 py-1 text-sm">
                            <option value="all">جميع العملاء</option>
                            <option value="client1">عميل 1</option>
                            <option value="client2">عميل 2</option>
                        </select>
                        <button onclick="filterOrders()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-sm">تصفية</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="p-3 text-right">اسم العميل</th>
                                <th class="p-3 text-right">الوقت</th>
                                <th class="p-3 text-right">التاريخ</th>
                                <th class="p-3 text-right">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">عميل 1</td>
                                <td class="p-3">12:30 م</td>
                                <td class="p-3">1446-12-15</td>
                                <td class="p-3">
                                    <button onclick="showOrderDetails(1)" class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded">عرض التفاصيل</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">عميل 2</td>
                                <td class="p-3">01:45 م</td>
                                <td class="p-3">1446-12-15</td>
                                <td class="p-3">
                                    <button onclick="showOrderDetails(2)" class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded">عرض التفاصيل</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">إجمالي الطلبات اليوم</p>
                            <h3 class="text-xl font-bold">24</h3>
                        </div>
                        <div class="bg-primary-100 p-3 rounded-full">
                            <span class="text-primary-600">📦</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">إجمالي المبيعات</p>
                            <h3 class="text-xl font-bold">1,245 ر.س</h3>
                        </div>
                        <div class="bg-primary-100 p-3 rounded-full">
                            <span class="text-primary-600">💰</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">متوسط قيمة الطلب</p>
                            <h3 class="text-xl font-bold">51.87 ر.س</h3>
                        </div>
                        <div class="bg-primary-100 p-3 rounded-full">
                            <span class="text-primary-600">📊</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال تفاصيل الطلب -->
    <div id="orderModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">تفاصيل الطلب</h3>
                <button onclick="closeOrderModal()" class="text-gray-500">✖</button>
            </div>

            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">اسم العميل:</span>
                    <span id="modalClientName" class="font-medium">عميل 1</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-500">وقت الطلب:</span>
                    <span id="modalOrderTime">12:30 م</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">تاريخ الطلب:</span>
                    <span id="modalOrderDate">1446-12-15</span>
                </div>
            </div>

            <div class="border rounded-lg overflow-hidden mb-4">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="p-3 text-right">الصنف</th>
                            <th class="p-3 text-right">الكمية</th>
                            <th class="p-3 text-right">السعر</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" id="orderItems">
                        <!-- سيتم ملؤها بالجافاسكريبت -->
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-100 p-4 rounded-lg">
                <div class="flex justify-between mb-2">
                    <span>المجموع الفرعي:</span>
                    <span id="modalSubtotal">$100.50</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>الخصومات:</span>
                    <span id="modalDiscounts">-$8.00</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>الضريبة (12%):</span>
                    <span id="modalTax">$11.20</span>
                </div>
                <div class="flex justify-between font-bold text-lg mt-3 pt-3 border-t">
                    <span>الإجمالي:</span>
                    <span id="modalTotal">$93.46</span>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button onclick="printOrder()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">طباعة</button>
                <button onclick="completeOrder()" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded">إتمام الطلب</button>
            </div>
        </div>
    </div>

    <script>
        function filterOrders() {
            const clientFilter = document.getElementById('clientFilter').value;
            document.querySelectorAll('tbody tr').forEach(row => {
                const clientName = row.children[0].textContent.toLowerCase();
                row.classList.toggle('hidden',
                    clientFilter !== 'all' &&
                    !clientName.includes(clientFilter.toLowerCase())
                );
            });
        }

        function showOrderDetails(orderId) {
            // هنا يمكنك جلب بيانات الطلب من الخادم باستخدام AJAX
            // لأغراض العرض، سنستخدم بيانات ثابتة

            document.getElementById('modalClientName').textContent = 'عميل 1';
            document.getElementById('modalOrderTime').textContent = '12:30 م';
            document.getElementById('modalOrderDate').textContent = '1446-12-15';

            // تفاصيل العناصر
            const itemsContainer = document.getElementById('orderItems');
            itemsContainer.innerHTML = `
                <tr>
                    <td class="p-3">T-Bone Stake</td>
                    <td class="p-3">2</td>
                    <td class="p-3">$66.00</td>
                </tr>
                <tr>
                    <td class="p-3">Soup of the Day</td>
                    <td class="p-3">1</td>
                    <td class="p-3">$7.50</td>
                </tr>
                <tr>
                    <td class="p-3">Pancakes</td>
                    <td class="p-3">2</td>
                    <td class="p-3">$27.00</td>
                </tr>
            `;

            // الملخص المالي
            document.getElementById('modalSubtotal').textContent = '$100.50';
            document.getElementById('modalDiscounts').textContent = '-$8.00';
            document.getElementById('modalTax').textContent = '$11.20';
            document.getElementById('modalTotal').textContent = '$93.46';

            document.getElementById('orderModal').classList.remove('hidden');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }

        function printOrder() {
            // يمكنك تنفيذ وظيفة الطباعة هنا
            alert('سيتم فتح نافذة الطباعة');
        }

        function completeOrder() {
            // يمكنك تنفيذ وظيفة إتمام الطلب هنا
            alert('تم إتمام الطلب بنجاح');
            closeOrderModal();
        }
    </script>
</body>
</html>
