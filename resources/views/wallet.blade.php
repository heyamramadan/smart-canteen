<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>شحن رصيد أولياء الأمور - لوحة تحكم المقصف</title>
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

        @keyframes fade-in-out {
            0%, 100% { opacity: 0; transform: translateY(-10px); }
            10%, 90% { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-out {
            animation: fade-in-out 3s ease-in-out forwards;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
           @include('layouts.sidebar')

        <!-- المحتوى الرئيسي -->
        <div class="flex-1 p-6 overflow-auto">
            <!-- عنوان الصفحة -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4">
                <h2 class="text-lg font-bold text-primary-700 flex items-center">
                    <span class="ml-2">💳</span>
                    شحن رصيد أولياء الأمور
                </h2>
            </div>

            <!-- قائمة أولياء الأمور -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-md font-semibold text-gray-700">قائمة أولياء الأمور</h3>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <input type="text" id="parentFilter" placeholder="ابحث عن ولي أمر..."
                               class="border rounded-lg px-3 py-1 text-sm w-64">
                        <button onclick="filterParents()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-sm transition">
                            بحث
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-right text-sm text-gray-500">#</th>
                                <th class="p-3 text-right text-sm text-gray-500">اسم ولي الأمر</th>
                                <th class="p-3 text-right text-sm text-gray-500">البريد الإلكتروني</th>
                                <th class="p-3 text-right text-sm text-gray-500">رقم الهاتف</th>
                                <th class="p-3 text-right text-sm text-gray-500">الرصيد الحالي</th>
                                <th class="p-3 text-right text-sm text-gray-500">الحالة</th>
                                <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                            </tr>
                        </thead>
                     <tbody class="divide-y divide-gray-200">
    @foreach ($parents as $index => $parent)
        <tr class="hover:bg-gray-50 transition">
            <td class="p-3 text-sm">{{ $index + 1 }}</td>
            <td class="p-3 text-sm">{{ $parent->full_name }}</td>
            <td class="p-3 text-sm">{{ $parent->email }}</td>
            <td class="p-3 text-sm">{{ $parent->phone_number }}</td>
            <td class="p-3 text-sm font-medium">{{ number_format($parent->parent->wwallet->balance ?? 0, 2) }} ر.س</td>
            <td class="p-3 text-sm">
                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                    نشط
                </span>
            </td>
            <td class="p-3 text-sm">
                <button onclick="showChargeModal({{ $parent->id }}, '{{ $parent->full_name }}', '{{ $parent->email }}', '{{ $parent->phone_number }}', {{ $parent->parent->wallet->balance ?? 0 }})"
                        class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded text-sm transition">
                    شحن الرصيد
                </button>
            </td>
        </tr>
    @endforeach
</tbody>

                    </table>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <div class="text-sm text-gray-500">عرض 1 إلى 4 من 4 مدخلات</div>
                    <div class="flex space-x-2 space-x-reverse">
                        <button class="px-3 py-1 border rounded-lg text-gray-700 bg-gray-100">السابق</button>
                        <button class="px-3 py-1 border rounded-lg text-gray-700 bg-gray-100">التالي</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal شحن الرصيد -->
    <div id="chargeModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">شحن رصيد ولي الأمر</h3>
                <button onclick="closeChargeModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <div class="flex items-center mb-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 font-bold">و</div>
                    <div class="mr-3">
                        <h4 id="modalParentName" class="font-medium">أحمد محمد</h4>
                        <p id="modalParentContact" class="text-sm text-gray-500">ahmed@example.com | 0501234567</p>
                    </div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">الرصيد الحالي:</span>
                        <span id="modalCurrentBalance" class="text-sm font-medium">150.00 ر.س</span>
                    </div>
                </div>
            </div>

            <form id="chargeForm">
                <input type="hidden" id="parentId">

                <div class="mb-4">
                    <label class="block text-sm text-gray-600 mb-1">المبلغ المطلوب شحنه</label>
                    <div class="relative">
                        <input type="number" id="amount" min="1" step="1" placeholder="أدخل المبلغ بالريال السعودي"
                               class="w-full pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <span class="absolute right-3 top-2.5 text-gray-400">ر.س</span>
                    </div>
                </div>

                <div class="mb-4 hidden" id="paymentDetailsSection">
                    <label class="block text-sm text-gray-600 mb-1">تفاصيل الدفع</label>
                    <textarea id="paymentDetails" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="أدخل تفاصيل الدفع (مثل رقم المرجع أو التفاصيل الأخرى)"></textarea>
                </div>

                <div class="mt-6">
                    <button type="button" onclick="submitCharge()" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-2 px-4 rounded-lg transition flex items-center justify-center">
                        <span class="ml-2">💳</span> تنفيذ عملية الشحن
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal نجاح العملية -->
    <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">تمت العملية بنجاح!</h3>
                <p class="text-gray-600 mb-4" id="successMessage">تم شحن المحفظة بمبلغ 100 ر.س بنجاح.</p>
                <button onclick="closeSuccessModal()" class="px-6 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                    تم
                </button>
            </div>
        </div>
    </div>

    <script>
        // عرض تفاصيل الدفع عند اختيار طريقة دفع معينة
        document.getElementById('paymentMethod').addEventListener('change', function() {
            const paymentDetailsSection = document.getElementById('paymentDetailsSection');
            if (this.value === 'bank_transfer' || this.value === 'cash') {
                paymentDetailsSection.classList.remove('hidden');
            } else {
                paymentDetailsSection.classList.add('hidden');
            }
        });

        // عرض مودال الشحن
        function showChargeModal(id, name, email, phone, balance) {
            document.getElementById('parentId').value = id;
            document.getElementById('modalParentName').textContent = name;
            document.getElementById('modalParentContact').textContent = email + ' | ' + phone;
            document.getElementById('modalCurrentBalance').textContent = balance.toFixed(2) + ' ر.س';

            document.getElementById('chargeForm').reset();
            document.getElementById('paymentDetailsSection').classList.add('hidden');

            document.getElementById('chargeModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // إغلاق مودال الشحن
        function closeChargeModal() {
            document.getElementById('chargeModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // تصفية أولياء الأمور
        function filterParents() {
            const filter = document.getElementById('parentFilter').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const phone = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                if (name.includes(filter) || email.includes(filter) || phone.includes(filter)) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });
        }

        // تنفيذ عملية الشحن
        function submitCharge() {
            const parentId = document.getElementById('parentId').value;
            const amount = parseFloat(document.getElementById('amount').value);
            const paymentMethod = document.getElementById('paymentMethod').value;
            const paymentDetails = document.getElementById('paymentDetails').value;

            if (!amount || amount <= 0) {
                alert('الرجاء إدخال مبلغ صحيح');
                return;
            }

            if (!paymentMethod) {
                alert('الرجاء اختيار طريقة الدفع');
                return;
            }

            if ((paymentMethod === 'bank_transfer' || paymentMethod === 'cash') && !paymentDetails) {
                alert('الرجاء إدخال تفاصيل الدفع');
                return;
            }

            // هنا يتم إرسال البيانات إلى الخادم (في تطبيق حقيقي)
            console.log('شحن الرصيد:', {
                parentId,
                amount,
                paymentMethod,
                paymentDetails
            });

            // عرض رسالة النجاح
            document.getElementById('successMessage').textContent = `تم شحن رصيد ولي الأمر بمبلغ ${amount.toFixed(2)} ر.س بنجاح.`;
            closeChargeModal();
            openSuccessModal();
        }

        // فتح مودال النجاح
        function openSuccessModal() {
            document.getElementById('successModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // إغلاق مودال النجاح
        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            // يمكن هنا إعادة تحميل البيانات لتحديث الرصيد الجديد
        }
    </script>
</body>
</html>
