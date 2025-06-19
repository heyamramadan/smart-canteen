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
                        <img src="https://via.placeholder.com/150" alt="صورة المستخدم" class="profile-image">
                        <button onclick="document.getElementById('profileImageInput').click()"
                                class="absolute bottom-0 right-0 bg-primary-500 text-white p-2 rounded-full hover:bg-primary-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <input type="file" id="profileImageInput" class="hidden" accept="image/*">
                    </div>

                    <!-- معلومات المستخدم الأساسية -->
                    <div class="text-center md:text-right">
                        <h3 class="text-xl font-semibold text-gray-800">محمد أحمد</h3>
                        <p class="text-gray-600">mohamed@example.com</p>
                        <p class="text-gray-600">+966 50 123 4567</p>
                        <p class="mt-3">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">موظف مقصف</span>
                        </p>
                    </div>
                </div>

                <!-- نموذج تعديل البيانات -->
                <form id="profileForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- الاسم الكامل -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
                            <input type="text" name="full_name" value="محمد أحمد" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="mohamed@example.com" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>

                        <!-- رقم الهاتف -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">رقم الهاتف</label>
                            <input type="tel" name="phone" value="+966501234567" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>

                        <!-- الدور/الصلاحية -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الدور</label>
                            <select name="role" class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="employee">موظف مقصف</option>
                                <option value="admin">مدير النظام</option>
                                <option value="supervisor">مشرف</option>
                            </select>
                        </div>

                        <!-- كلمة المرور -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">كلمة المرور الجديدة</label>
                            <div class="relative">
                                <input type="password" name="password"
                                       class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <button type="button" onclick="togglePassword(this)"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                                    👁️
                                </button>
                            </div>
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">تأكيد كلمة المرور</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation"
                                       class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <button type="button" onclick="togglePassword(this)"
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                                    👁️
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                        <button type="button" onclick="resetForm()" class="px-6 py-2 rounded-lg border border-gray-300 hover:border-gray-400 transition">
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

    // إعادة تعيين النموذج
    function resetForm() {
        document.getElementById('profileForm').reset();
        // يمكنك إعادة تعيين القيم الافتراضية هنا إذا لزم الأمر
    }

    // معالجة تغيير الصورة
    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.querySelector('.profile-image').src = event.target.result;
                // هنا يمكنك إضافة كود لإرسال الصورة إلى الخادم
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // معالجة إرسال النموذج
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // هنا يمكنك إضافة كود لإرسال البيانات إلى الخادم
        alert('تم حفظ التغييرات بنجاح!');
    });
</script>

</body>
</html>
