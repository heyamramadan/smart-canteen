<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>سجل الأرشيف - لوحة تحكم المقصف</title>
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
   <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center flex-wrap gap-4">
    <h2 class="text-lg font-bold text-primary-700 flex items-center">
        <span class="ml-2">🗃️</span>
        سجل أرشيف المستخدمين
    </h2>
    <div class="relative">
        <input
            type="text"
            id="searchInput"
            placeholder="ابحث عن مستخدم..."
            class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
        />
        <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
    </div>
</div>


@if (session('success'))
    <div
        id="successMessage"
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-100 text-green-800 border border-green-300 px-6 py-4 rounded-lg shadow-lg z-50 text-center"
    >
        {{ session('success') }}
    </div>
@endif


        <!-- جدول المستخدمين المؤرشفين -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-right text-sm text-gray-500">اسم المستخدم</th>
                        <th class="p-3 text-right text-sm text-gray-500">الاسم الكامل</th>
                        <th class="p-3 text-right text-sm text-gray-500">البريد الإلكتروني</th>
                        <th class="p-3 text-right text-sm text-gray-500">رقم الهاتف</th>
                        <th class="p-3 text-right text-sm text-gray-500">الدور</th>
                        <th class="p-3 text-right text-sm text-gray-500">تاريخ التسجيل</th>
                        <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody id="archivedUsersTable" class="divide-y divide-gray-200">
                    @forelse ($archivedUsers as $user)
                        <tr class="bg-gray-50 text-gray-500 hover:bg-gray-100 transition">
                            <td class="p-3 text-sm">{{ $user->username }}</td>
                            <td class="p-3 text-sm">{{ $user->full_name }}</td>
                            <td class="p-3 text-sm">{{ $user->email }}</td>
                            <td class="p-3 text-sm">{{ $user->phone_number }}</td>
                            <td class="p-3 text-sm">{{ $user->role }}</td>
                            <td class="p-3 text-sm">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="p-3">
                                <!-- زر استعادة يفتح المودال -->
                                <button
                                    onclick="openRestoreModal({{ $user->id }}, '{{ $user->username }}')"
                                    class="text-green-600 border border-green-600 px-3 py-1 rounded-lg hover:bg-green-600 hover:text-white transition"
                                >
                                    ♻️ استعادة
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">لا يوجد مستخدمون مؤرشفون حالياً.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4 px-4">
                    {{ $archivedUsers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال التأكيد -->
<div
    id="restoreModal"
    class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center hidden"
>
    <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">هل أنت متأكد من الاستعادة؟</h3>
        <p class="mb-6 text-gray-600" id="modalUserName"></p>
        <div class="flex justify-end space-x-3 rtl:space-x-reverse">
            <button
                onclick="closeRestoreModal()"
                class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 transition"
            >
                إلغاء
            </button>
            <form id="restoreForm" method="POST" style="display:inline;">
                @csrf
          <button
    type="submit"
    class="px-4 py-2 rounded bg-orange-600 text-white hover:bg-orange-700 transition"
>
    استعادة
</button>

            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('restoreModal');
    const modalUserName = document.getElementById('modalUserName');
    const restoreForm = document.getElementById('restoreForm');

    function openRestoreModal(userId, username) {
        modalUserName.textContent = `هل تريد استعادة المستخدم: ${username}؟`;
        restoreForm.action = `/archived-users/${userId}/restore`;
        modal.classList.remove('hidden');
    }

    function closeRestoreModal() {
        modal.classList.add('hidden');
    }

    // اغلاق المودال لو ضغط المستخدم خارج محتوى النافذة
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeRestoreModal();
        }
    });
        // إخفاء رسالة النجاح بعد 4 ثواني
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        setTimeout(() => {
            successMessage.classList.add('opacity-0', 'transition', 'duration-500');
            setTimeout(() => successMessage.remove(), 500); // إزالة العنصر بعد التلاشي
        }, 4000);
    }
        document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.trim();
        fetch(`/archived-users/search?query=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => updateArchivedUsersTable(data))
            .catch(err => console.error(err));
    });

    function updateArchivedUsersTable(users) {
        const tbody = document.getElementById('archivedUsersTable');
        tbody.innerHTML = '';

        if (users.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-gray-500">لا يوجد نتائج مطابقة.</td></tr>`;
            return;
        }

        users.forEach(user => {
            const row = document.createElement('tr');
            row.className = 'bg-gray-50 text-gray-500 hover:bg-gray-100 transition';
            row.innerHTML = `
                <td class="p-3 text-sm">${user.username}</td>
                <td class="p-3 text-sm">${user.full_name}</td>
                <td class="p-3 text-sm">${user.email}</td>
                <td class="p-3 text-sm">${user.phone_number}</td>
                <td class="p-3 text-sm">${user.role}</td>
                <td class="p-3 text-sm">${user.created_at}</td>
                <td class="p-3">
                    <button
                        onclick="openRestoreModal(${user.id}, '${user.username}')"
                        class="text-green-600 border border-green-600 px-3 py-1 rounded-lg hover:bg-green-600 hover:text-white transition"
                    >
                        ♻️ استعادة
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
</script>

</body>
</html>
