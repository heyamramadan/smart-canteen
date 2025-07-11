<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>شحن رصيد أولياء الأمور - لوحة تحكم المقصف</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <span class="ml-2">💰</span> شحن رصيد أولياء الأمور
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-md font-semibold text-gray-700">قائمة أولياء الأمور</h3>
                    <div class="flex items-center gap-2">
                        <input type="text" id="parentFilter" onkeyup="filterParents()" placeholder="ابحث عن ولي أمر..." class="border rounded-lg px-3 py-1 text-sm w-64">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="p-3 text-right">#</th>
                                <th class="p-3 text-right">اسم ولي الأمر</th>
                                <th class="p-3 text-right">البريد الإلكتروني</th>
                                <th class="p-3 text-right">رقم الهاتف</th>
                                <th class="p-3 text-right">الرصيد الحالي</th>
                                <th class="p-3 text-right">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            {{-- ✅ تعديل: استخدام متغير users$ الذي تم إرساله من المتحكم --}}
                            @forelse ($users as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">{{ $index + 1 }}</td>
                                {{-- ✅ تعديل: الوصول للبيانات مباشرة من متغير user$ --}}
                                <td class="p-3">{{ $user->full_name }}</td>
                                <td class="p-3">{{ $user->email }}</td>
                                <td class="p-3">{{ $user->phone_number ?? 'غير متوفر' }}</td>
                                {{-- ✅ تعديل: الوصول للرصيد من علاقة المحفظة المباشرة --}}
                                <td class="p-3 font-medium">{{ number_format($user->wallet->balance ?? 0, 2) }} د.ل</td>
                                <td class="p-3">
                                    {{-- ✅ تعديل: تمرير user->id مباشرة، والوصول للرصيد من علاقة المحفظة --}}
                                    <button onclick="showChargeModal({{ $user->id }}, '{{ $user->full_name }}', {{ $user->wallet->balance ?? 0 }}, {{ $user->wallet->daily_limit ?? 20 }})" class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded">
                                        شحن
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">لا يوجد أولياء أمور لعرضهم.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="chargeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50 transition-opacity">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-bold text-gray-800">شحن وتحديث الرصيد</h3>
                <button onclick="closeChargeModal()" class="text-gray-500 hover:text-red-600">✖</button>
            </div>
            <div class="mb-4">
                <p class="text-gray-600">ولي الأمر: <span id="modalParentName" class="font-medium text-gray-900"></span></p>
                <div class="bg-primary-100 text-primary-700 p-2 rounded mt-2 text-center">
                    <span>الرصيد الحالي: </span><strong id="modalCurrentBalance" class="text-lg"></strong>
                </div>
            </div>
            <form id="chargeForm" onsubmit="event.preventDefault(); submitCharge();" class="space-y-4">
                <input type="hidden" id="userIdInput">

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">المبلغ المراد شحنه</label>
                    <input type="number" id="amount" class="w-full border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:ring-primary-500 focus:border-primary-500" min="1" required>
                </div>

                <div>
                    <label for="daily_limit" class="block text-sm font-medium text-gray-700 mb-1">حد الإنفاق اليومي الجديد (اختياري)</label>
                    <input type="number" id="daily_limit" class="w-full border-gray-300 rounded-lg shadow-sm px-3 py-2 focus:ring-primary-500 focus:border-primary-500" min="0">
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    تنفيذ الشحن
                </button>
            </form>
        </div>
    </div>

    <div id="successModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-sm p-6 text-center">
            <div class="mb-4">
                <div class="w-16 h-16 bg-green-100 mx-auto flex items-center justify-center rounded-full">
                    <span class="text-4xl">✅</span>
                </div>
                <h3 class="text-xl font-bold mt-4 text-gray-800">تمت العملية بنجاح</h3>
                <p id="successMessage" class="text-gray-600 mt-2"></p>
            </div>
            <button onclick="closeSuccessModalAndReload()" class="w-full bg-primary-500 text-white px-4 py-2 rounded-lg font-semibold">رائع!</button>
        </div>
    </div>

  <script>
    // الحصول على التوكن من الميتا تاغ
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function showChargeModal(userId, name, balance, dailyLimit) {
        document.getElementById('userIdInput').value = userId;
        document.getElementById('modalParentName').textContent = name;
        document.getElementById('modalCurrentBalance').textContent = `${parseFloat(balance).toFixed(2)} د.ل`;
        document.getElementById('daily_limit').placeholder = `الحد الحالي: ${parseFloat(dailyLimit).toFixed(2)}`;
        document.getElementById('chargeForm').reset(); // لإفراغ حقل المبلغ
        document.getElementById('chargeModal').classList.remove('hidden');
    }

    function closeChargeModal() {
        document.getElementById('chargeModal').classList.add('hidden');
    }

    function closeSuccessModalAndReload() {
        document.getElementById('successModal').classList.add('hidden');
        window.location.reload(); // إعادة تحميل الصفحة لعرض البيانات المحدثة
    }

    function submitCharge() {
        const userId = document.getElementById('userIdInput').value;
        const amount = document.getElementById('amount').value;
        const daily_limit = document.getElementById('daily_limit').value;

        if (!amount || parseFloat(amount) <= 0) {
            alert('الرجاء إدخال مبلغ صحيح للشحن.');
            return;
        }

        const bodyData = {
            user_id: userId, // ✅ تعديل: إرسال user_id بدلاً من parent_id
            amount: parseFloat(amount)
        };

        if (daily_limit) {
            bodyData.daily_limit = parseFloat(daily_limit);
        }

        fetch("{{ route('wallet.charge') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify(bodyData)
        })
        .then(res => {
            if (!res.ok) {
                 return res.json().then(err => { throw new Error(err.message || "فشل في تنفيذ الشحن") });
            }
            return res.json();
        })
        .then(data => {
            document.getElementById('successMessage').textContent = data.message;
            closeChargeModal();
            document.getElementById('successModal').classList.remove('hidden');
        })
        .catch(err => {
            alert(err.message);
        });
    }

    function filterParents() {
        const filter = document.getElementById('parentFilter').value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            // البحث في اسم ولي الأمر والبريد الإلكتروني ورقم الهاتف
            const name = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();
            const phone = row.children[3].textContent.toLowerCase();
            const isVisible = name.includes(filter) || email.includes(filter) || phone.includes(filter);
            row.style.display = isVisible ? '' : 'none';
        });
    }
</script>
</body>
</html>