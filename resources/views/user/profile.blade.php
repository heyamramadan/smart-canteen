<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>الملف الشخصي - لوحة تحكم المقصف</title>
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
        .profile-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #FFEDD5;
        }

        /* رسالة التحديث في المنتصف */
        #successMessage {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #DCFCE7; /* أخضر فاتح */
            color: #166534; /* أخضر غامق */
            padding: 1rem 2rem;
            border-radius: 0.75rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            font-weight: 600;
            font-size: 1.125rem;
            display: none; /* مخفي افتراضياً */
            z-index: 1000;
            text-align: center;
            max-width: 90%;
            direction: rtl;
        }
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
    @include('layouts.sidebar')
    <!-- محتوى الملف الشخصي -->
    <div class="flex-1 p-6 overflow-auto">
        <!-- عنوان الصفحة -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <span class="ml-2">👤</span>
                الملف الشخصي
            </h2>
        </div>

        <!-- بطاقة الملف الشخصي -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex flex-col md:flex-row items-center gap-6 mb-8">
                    <!-- صورة المستخدم -->
                    <div class="relative">
                        <img
                            src="{{ $user->profile_image_url ? asset('storage/' . $user->profile_image_url) : 'https://via.placeholder.com/150' }}"
                            alt="صورة المستخدم"
                            class="profile-image"
                        >
                        <button onclick="document.getElementById('profileImageInput').click()"
                                class="absolute bottom-0 right-0 bg-primary-500 text-white p-2 rounded-full hover:bg-primary-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <input type="file" id="profileImageInput" name="profile_image" class="hidden" accept="image/*">
                    </div>

                    <!-- معلومات المستخدم الأساسية -->
                    <div class="text-center md:text-right">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $user->full_name }}</h3>
                        <p class="text-gray-600">اسم المستخدم: {{ $user->username }}</p>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <p class="text-gray-600">{{ $user->phone_number }}</p>
                        <p class="mt-3">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                @switch($user->role)
                                    @case('موظف')
                                        موظف مقصف
                                        @break
                                    @case('مسؤول')
                                        مدير النظام
                                        @break

                                @endswitch
                            </span>
                        </p>
                    </div>
                </div>

                <!-- نموذج تعديل البيانات -->
                <form id="profileForm" class="space-y-4" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- اسم المستخدم -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">اسم المستخدم</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @error('username')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الاسم الكامل -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @error('full_name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- رقم الهاتف -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">رقم الهاتف</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            @error('phone_number')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الدور/الصلاحية (عرض فقط) -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الدور</label>
                            <p class="w-full border border-orange-300 rounded-lg px-4 py-2 bg-gray-100 text-gray-700 select-none cursor-not-allowed">
                                @switch($user->role)
                                    @case('موظف')
                                        موظف مقصف
                                        @break
                                    @case('مسؤول')
                                       مسؤول
                                        @break
                                    @endswitch
                            </p>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        </div>

                        <!-- كلمة المرور الحالية -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">كلمة المرور الحالية</label>
                            <div class="relative">
                                <input type="password" name="current_password"
                                       class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" autocomplete="current-password" placeholder="أدخل كلمة المرور الحالية إذا كنت تريد التغيير">
                                <button type="button" onclick="togglePassword(this)"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                                    👁️
                                </button>
                            </div>
                            @error('current_password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- كلمة المرور الجديدة -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">كلمة المرور الجديدة</label>
                            <div class="relative">
                                <input type="password" name="new_password"
                                       class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500" autocomplete="new-password" placeholder="اتركها فارغة إذا لم تريد التغيير">
                                <button type="button" onclick="togglePassword(this)"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                                    👁️
                                </button>
                            </div>
                            @error('new_password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                        <button type="reset" class="px-6 py-2 rounded-lg border border-gray-300 hover:border-gray-400 transition">
                            إلغاء التغييرات
                        </button>
                        <button type="submit" class="px-6 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white transition">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- رسالة التحديث تظهر في المنتصف -->
@if(session('success'))
    <div id="successMessage">
        {{ session('success') }}
    </div>
@endif

<script>
    // عرض/إخفاء كلمة المرور
    function togglePassword(button) {
        const input = button.previousElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            button.textContent = '🙈';
        } else {
            input.type = 'password';
            button.textContent = '👁️';
        }
    }

    // عرض رسالة النجاح ثم إخفائها بعد 4 دقائق
    document.addEventListener('DOMContentLoaded', () => {
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'block';

           
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 4000);
        }
    });
</script>

</body>
</html>
