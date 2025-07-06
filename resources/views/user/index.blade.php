<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                        <input id="searchInput" type="text" placeholder="ابحث عن مستخدم..."
                               class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" />
                        <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
                    </div>

                    <!-- زر إضافة مستخدم -->
                    <button onclick="openModal()" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
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
                                <th class="p-3 text-right text-sm text-gray-500">اسم المستخدم</th>
                                <th class="p-3 text-right text-sm text-gray-500">الاسم الكامل</th>
                                <th class="p-3 text-right text-sm text-gray-500">البريد الإلكتروني</th>
                                <th class="p-3 text-right text-sm text-gray-500">رقم الهاتف</th>
                                <th class="p-3 text-right text-sm text-gray-500">الدور</th>
                                <th class="p-3 text-right text-sm text-gray-500">تاريخ التسجيل</th>
                                <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody" class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 transition {{ $user->deleted_at ? 'bg-gray-100 text-gray-400' : '' }}">
                                    <td class="p-3 text-sm">{{ $user->username }}</td>
                                    <td class="p-3 text-sm">{{ $user->full_name }}</td>
                                    <td class="p-3 text-sm">{{ $user->email }}</td>
                                    <td class="p-3 text-sm">{{ $user->phone_number }}</td>
                                    <td class="p-3 text-sm">{{ $user->role }}</td>
                                    <td class="p-3 text-sm">{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="p-3 flex items-center space-x-2 space-x-reverse">
                                        @if ($user->trashed())
                                            <form method="POST" action="{{ route('users.restore', $user->id) }}" class="restore-form">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-100 transition">
                                                    ♻️ استعادة
                                                </button>
                                            </form>
                                        @else
                                            <button onclick="openEditModal('{{ $user->id }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->full_name }}', '{{ $user->role }}', '{{ $user->phone_number }}')" class="text-primary-500 hover:text-primary-700 p-1 rounded hover:bg-primary-100 transition">
                                                ✏️ تعديل
                                            </button>

                                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="archive-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-100 transition">
                                                    🗑️ أرشفة
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- مودالات وإضافات جافاسكريبت (فتح/إغلاق المودالات) -->
    <script>
        // ... (احتفظ بالكود الذي لديك لفتح/إغلاق المودالات)

        // بحث حي (Live Search)
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const usersTableBody = document.getElementById('usersTableBody');

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                fetch(`/users/search?query=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(users => {
                    usersTableBody.innerHTML = ''; // مسح المحتوى القديم

                    if (users.length === 0) {
                        usersTableBody.innerHTML = `<tr><td colspan="7" class="p-4 text-center text-gray-500">لا توجد نتائج</td></tr>`;
                        return;
                    }

                    users.forEach(user => {
                        const isTrashed = user.deleted_at !== null;

                        const tr = document.createElement('tr');
                        tr.className = `hover:bg-gray-50 transition ${isTrashed ? 'bg-gray-100 text-gray-400' : ''}`;

                        // تفادي مشاكل علامات الاقتباس في البيانات (هنا مبسط)
                        const username = user.username || '';
                        const email = user.email || '';
                        const full_name = user.full_name || '';
                        const role = user.role || '';
                        const phone_number = user.phone_number || '';

                        tr.innerHTML = `
                            <td class="p-3 text-sm">${username}</td>
                            <td class="p-3 text-sm">${full_name}</td>
                            <td class="p-3 text-sm">${email}</td>
                            <td class="p-3 text-sm">${phone_number}</td>
                            <td class="p-3 text-sm">${role}</td>
                            <td class="p-3 text-sm">${new Date(user.created_at).toISOString().slice(0,10)}</td>
                            <td class="p-3 flex items-center space-x-2 space-x-reverse">
                                ${isTrashed
                                    ? `<form method="POST" action="/users/${user.id}/restore" class="restore-form">@csrf<button type="submit" class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-100 transition">♻️ استعادة</button></form>`
                                    : `<button onclick="openEditModal('${user.id}', '${username}', '${email}', '${full_name}', '${role}', '${phone_number}')" class="text-primary-500 hover:text-primary-700 p-1 rounded hover:bg-primary-100 transition">✏️ تعديل</button>
                                       <form method="POST" action="/users/${user.id}" class="archive-form">@csrf @method('DELETE')
                                           <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-100 transition">🗑️ أرشفة</button>
                                       </form>`
                                }
                            </td>
                        `;
                        usersTableBody.appendChild(tr);
                    });
                })
                .catch(err => console.error('خطأ في جلب البيانات:', err));
            });
        });
    </script>
</body>
</html>
