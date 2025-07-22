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
        <tr class="hover:bg-gray-100 transition {{ $user->deleted_at ? 'bg-gray-50 text-gray-400' : '' }}">
            <td class="p-3 text-sm whitespace-nowrap">{{ $user->username }}</td>
            <td class="p-3 text-sm whitespace-nowrap">{{ $user->full_name }}</td>
            <td class="p-3 text-sm whitespace-nowrap">{{ $user->email }}</td>
            <td class="p-3 text-sm whitespace-nowrap">{{ $user->phone_number }}</td>
            <td class="p-3 text-sm whitespace-nowrap">{{ $user->role }}</td>
            <td class="p-3 text-sm whitespace-nowrap">{{ $user->created_at->format('Y-m-d') }}</td>
            <td class="p-3 flex items-center space-x-2 space-x-reverse whitespace-nowrap">
         <button onclick="openEditModal({{ $user->id }}, '{{ $user->username }}', '{{ $user->email }}', '{{ $user->full_name }}', '{{ $user->role }}', '{{ $user->phone_number }}')"
    class="bg-white text-orange-500 border border-orange-500 px-3 py-1 rounded-lg hover:bg-orange-500 hover:text-white transition flex items-center space-x-1 space-x-reverse">
    <span>✏️</span>
    <span>تعديل</span>
</button>

<form method="POST" action="{{ route('users.destroy', $user->id) }}" class="archive-form">
    @csrf
    @method('DELETE')
    <button type="button"
        class="bg-white text-orange-500 border border-orange-500 px-3 py-1 rounded-lg hover:bg-orange-500 hover:text-white transition flex items-center space-x-1 space-x-reverse archive-btn"
        data-user-id="{{ $user->id }}">
        <span>🗑️</span>
        <span>أرشفة</span>
    </button>
</form>

            </td>
        </tr>
    @endforeach
</tbody>

                    </table>
                        <div class="mt-4 px-4">
        {{ $users->links() }}
    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- مودال تعديل المستخدم -->
<div id="editUserModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
            <h3 class="text-lg font-bold text-primary-700">تعديل بيانات المستخدم</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">✖</button>
        </div>
        <div class="p-6">
            <form method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" id="edit_user_id" />
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">اسم المستخدم</label>
                        <input type="text" name="username" id="edit_username"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">البريد الإلكتروني</label>
                        <input type="email" name="email" id="edit_email"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
                        <input type="text" name="full_name" id="edit_full_name"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الدور</label>
                        <select name="role" id="edit_role"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2">
                            <option value="مسؤول">مسؤول</option>
                            <option value="موظف">موظف</option>
                            <option value="ولي أمر">ولي أمر</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">رقم الهاتف</label>
                        <input type="text" name="phone_number" id="edit_phone_number"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">كلمة المرور الحالية</label>
                        <input type="password" name="current_password"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">كلمة المرور الجديدة</label>
                        <input type="password" name="new_password"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" name="new_password_confirmation"
                            class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                    </div>
                </div>
<!-- زر تصفير كلمة المرور -->
<div class="pt-2">
    <button type="button" onclick="resetPasswordToDefault()"
        class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
        🔄 تصفير كلمة المرور إلى القيمة الافتراضية
    </button>
</div>

                <div class="pt-4 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- مودال إضافة مستخدم جديد -->
    <div id="userModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
                <h3 class="text-lg font-bold text-primary-700">إضافة مستخدم جديد</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    ✖
                </button>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                       <div>
    <label class="block text-sm text-gray-600 mb-1">اسم المستخدم</label>
    <input type="text" name="username" value="{{ old('username') }}" required class="w-full border border-orange-300 rounded-lg px-4 py-2" />
    @error('username') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
</div>
<div>
    <label class="block text-sm text-gray-600 mb-1">البريد الإلكتروني</label>
    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-orange-300 rounded-lg px-4 py-2" />
    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
</div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                            @error('full_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الدور</label>
                            <select name="role" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                                <option value="">اختر الدور</option>
                                <option value="مسؤول" {{ old('role') == 'مسؤول' ? 'selected' : '' }}>مسؤول</option>
                                <option value="موظف" {{ old('role') == 'موظف' ? 'selected' : '' }}>موظف</option>
                                <option value="ولي أمر" {{ old('role') == 'ولي أمر' ? 'selected' : '' }}>ولي أمر</option>
                            </select>
                            @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">كلمة المرور</label>
                            <input type="password" name="password" required class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" required class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                            @error('phone_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3 space-x-reverse">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            إلغاء
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                            حفظ المستخدم
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- مودال تأكيد الأرشفة -->
<div id="confirmArchiveModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-primary-700 mb-4">تأكيد الأرشفة</h3>
        <p class="mb-6">هل أنت متأكد من أرشفة هذا المستخدم؟</p>
        <div class="flex justify-end space-x-3 space-x-reverse">
            <button id="cancelArchiveBtn" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                إلغاء
            </button>
            <button id="confirmArchiveBtn" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                أرشفة
            </button>
        </div>
    </div>
</div>




    @if(session('success'))
    <div id="flashMessage" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-xl animate-fade-in-out transition-opacity duration-300">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <script>
        function openModal() {
            document.getElementById('userModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('userModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        function openEditModal(id, username, email, full_name, role, phone) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_full_name').value = full_name;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_phone_number').value = phone;

            // تحديث الفورم بالمسار المناسب
            document.getElementById('editUserForm').action = '/users/' + id;

            document.getElementById('editUserModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
function resetPasswordToDefault() {
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'reset_password';
    input.value = '1';
    document.getElementById('editUserForm').appendChild(input);
    document.getElementById('editUserForm').submit();
}

        // فتح المودال تلقائيًا إذا كان هناك أخطاء في التحقق
        @if ($errors->any())
            openModal();
        @endif

        // إخفاء رسالة النجاح بعد 3 ثوانٍ
        setTimeout(() => {
            const msg = document.getElementById('flashMessage');
            if (msg) msg.remove();
        }, 3000);

        // مودال تأكيد الأرشفة
        document.addEventListener('DOMContentLoaded', function() {
            const archiveModal = document.getElementById('confirmArchiveModal');
            const cancelArchiveBtn = document.getElementById('cancelArchiveBtn');
            const confirmArchiveBtn = document.getElementById('confirmArchiveBtn');
            let currentArchiveForm = null;

            // عند الضغط على زر الأرشفة
            document.querySelectorAll('.archive-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentArchiveForm = this.closest('form');
                    archiveModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
            });

            // عند الضغط على إلغاء الأرشفة
            cancelArchiveBtn.addEventListener('click', function() {
                archiveModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                currentArchiveForm = null;
            });

            // عند الضغط على تأكيد الأرشفة
            confirmArchiveBtn.addEventListener('click', function() {
                if (currentArchiveForm) {
                    currentArchiveForm.submit();
                }
            });

            // إغلاق المودال عند الضغط خارج المحتوى
            archiveModal.addEventListener('click', function(e) {
                if (e.target === archiveModal) {
                    archiveModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    currentArchiveForm = null;
                }
            });

        });
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

                // بناء صف المستخدم كـ HTML (ممكن تضيف أزرار التعديل/الأرشفة حسب حاجتك)
                const row = document.createElement('tr');
                row.className = `hover:bg-gray-50 transition ${isTrashed ? 'bg-gray-100 text-gray-400' : ''}`;

               row.innerHTML = `
    <td class="p-3 text-sm">${user.username}</td>
    <td class="p-3 text-sm">${user.full_name}</td>
    <td class="p-3 text-sm">${user.email}</td>
    <td class="p-3 text-sm">${user.phone_number || ''}</td>
    <td class="p-3 text-sm">${user.role}</td>
    <td class="p-3 text-sm">${new Date(user.created_at).toISOString().slice(0,10)}</td>
    <td class="p-3 flex items-center space-x-2 space-x-reverse whitespace-nowrap">
        ${isTrashed ? `
            <form method="POST" action="/users/${user.id}/restore" class="restore-form">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                <button type="submit"
                    class="bg-white text-green-600 border border-green-600 px-3 py-1 rounded-lg hover:bg-green-600 hover:text-white transition flex items-center space-x-1 space-x-reverse">
                    ♻️ <span>استعادة</span>
                </button>
            </form>
        ` : `
            <button onclick="openEditModal(${user.id}, '${user.username}', '${user.email}', '${user.full_name}', '${user.role}', '${user.phone_number}')"
                class="bg-white text-orange-500 border border-orange-500 px-3 py-1 rounded-lg hover:bg-orange-500 hover:text-white transition flex items-center space-x-1 space-x-reverse">
                ✏️ <span>تعديل</span>
            </button>
            <form method="POST" action="/users/${user.id}" class="archive-form">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="button"
                    class="bg-white text-orange-500 border border-orange-500 px-3 py-1 rounded-lg hover:bg-orange-500 hover:text-white transition flex items-center space-x-1 space-x-reverse archive-btn"
                    data-user-id="${user.id}">
                    🗑️ <span>أرشفة</span>
                </button>
            </form>
        `}
    </td>
`;

                usersTableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching users:', error);
        });
    });
});

    </script>
</body>
</html>
