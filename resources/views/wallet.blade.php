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
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        @include('layouts.sidebar')

        <div class="flex-1 p-6 overflow-auto">
            <div class="bg-white rounded-xl shadow-lg mb-6 p-4">
                <h2 class="text-lg font-bold text-primary-700 flex items-center">
                    <span class="ml-2">💳</span> شحن رصيد أولياء الأمور
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-md font-semibold text-gray-700">قائمة أولياء الأمور</h3>
                    <div class="flex items-center gap-2">
                        <input type="text" id="parentFilter" placeholder="ابحث عن ولي أمر..." class="border rounded-lg px-3 py-1 text-sm w-64">
                        <button onclick="filterParents()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-lg text-sm">بحث</button>
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
                                <th class="p-3 text-right">الحالة</th>
                                <th class="p-3 text-right">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($parents as $index => $parent)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3">{{ $parent->full_name }}</td>
                                <td class="p-3">{{ $parent->email }}</td>
                                <td class="p-3">{{ $parent->phone_number }}</td>
                                <td class="p-3 font-medium">{{ number_format($parent->parent->wallet->balance ?? 0, 2) }} ر.س</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">نشط</span>
                                </td>
                                <td class="p-3">
                                    <button onclick="showChargeModal({{ $parent->parent->parent_id }}, '{{ $parent->full_name }}', '{{ $parent->email }}', '{{ $parent->phone_number }}', {{ $parent->parent->wallet->balance ?? 0 }})" class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded">شحن</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال الشحن -->
    <div id="chargeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">شحن الرصيد</h3>
                <button onclick="closeChargeModal()" class="text-gray-500">✖</button>
            </div>
            <div class="mb-4">
                <h4 id="modalParentName" class="font-medium"></h4>
                <p id="modalParentContact" class="text-sm text-gray-500"></p>
                <div class="bg-gray-100 p-2 rounded mt-2">
                    <span>الرصيد الحالي: </span><span id="modalCurrentBalance"></span>
                </div>
            </div>
            <form id="chargeForm" onsubmit="event.preventDefault(); submitCharge();">
                <input type="hidden" id="parentId">

                <label class="block text-sm mb-1">المبلغ</label>
                <input type="number" id="amount" class="w-full border rounded px-3 py-2 mb-3" min="1">

                <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white py-2 rounded">تنفيذ الشحن</button>
            </form>
        </div>
    </div>

    <!-- مودال النجاح -->
    <div id="successModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 text-center">
            <div class="mb-4">
                <div class="w-16 h-16 bg-green-100 mx-auto flex items-center justify-center rounded-full">
                    ✅
                </div>
                <h3 class="text-lg font-bold mt-2">تمت العملية بنجاح</h3>
                <p id="successMessage" class="text-gray-600 mt-1"></p>
            </div>
            <button onclick="closeSuccessModal()" class="bg-primary-500 text-white px-4 py-2 rounded">تم</button>
        </div>
    </div>

  <script>
    function showChargeModal(id, name, email, phone, balance) {
        document.getElementById('parentId').value = id;
        document.getElementById('modalParentName').textContent = name;
        document.getElementById('modalParentContact').textContent = `${email} | ${phone}`;
        document.getElementById('modalCurrentBalance').textContent = `${balance.toFixed(2)} ر.س`;
        document.getElementById('chargeForm').reset();
        document.getElementById('chargeModal').classList.remove('hidden');
    }

    function closeChargeModal() {
        document.getElementById('chargeModal').classList.add('hidden');
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.add('hidden');
    }

    function updateParentBalance(parentId, amount) {
        document.querySelectorAll('tbody tr').forEach(row => {
            const chargeButton = row.querySelector('button');
            if (chargeButton && chargeButton.getAttribute('onclick').includes(parentId)) {
                const balanceCell = row.querySelector('td:nth-child(5)');
                const currentBalance = parseFloat(balanceCell.textContent.replace(/[^\d.]/g, ''));
                const newBalance = currentBalance + amount;
                balanceCell.textContent = `${newBalance.toFixed(2)} ر.س`;
            }
        });
    }

    function submitCharge() {
        const amount = parseFloat(document.getElementById('amount').value);
        const parentId = document.getElementById('parentId').value;

        if (!amount || amount <= 0) return alert('أدخل مبلغًا صحيحًا');

        fetch("{{ route('wallet.charge') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                parent_id: parentId,
                amount: amount
            })
        })
        .then(res => {
            if (!res.ok) throw new Error("فشل في تنفيذ الشحن");
            return res.json();
        })
        .then(data => {
            document.getElementById('successMessage').textContent = data.message;
            closeChargeModal();
            document.getElementById('successModal').classList.remove('hidden');
            updateParentBalance(parentId, amount);
        })
        .catch(err => alert(err.message));
    }

    function filterParents() {
        const filter = document.getElementById('parentFilter').value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            const name = row.children[1].textContent.toLowerCase();
            const email = row.children[2].textContent.toLowerCase();
            const phone = row.children[3].textContent.toLowerCase();
            row.classList.toggle('hidden', !(name.includes(filter) || email.includes(filter) || phone.includes(filter)));
        });
    }
</script>
</body>
</html>
