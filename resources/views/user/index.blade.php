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
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 transition">
                                 <td class="p-3 text-sm">{{ $user->username }}</td>
                                 <td class="p-3 text-sm">{{ $user->full_name }}</td>
                                  <td class="p-3 text-sm">{{ $user->email }}</td>
                                   <td class="p-3 text-sm">{{ $user->phone_number }}</td>
                                    <td class="p-3 text-sm">{{ $user->role }}</td>
                                     <td class="p-3 text-sm">{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="p-3 flex items-center">
                                        <button class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">
                                            ✏️ تعديل
                                        </button>
                                        <button class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">
                                            🗑️ حذف
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding new user -->
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
                <form method="POST" action=""{{ route('users.store') }}"" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">اسم المستخدم</label>
                            <input type="text" name="username" value="{{ old('username') }}" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                            @error('username') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
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
                            <input type="password" name="password" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full border border-orange-300 rounded-lg px-4 py-2">
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
    </script>
</body>
</html>
