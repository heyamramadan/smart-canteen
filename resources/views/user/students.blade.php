<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إدارة الطلاب - لوحة تحكم المقصف</title>
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

        <!-- محتوى إدارة الطلاب -->
        <div class="flex-1 p-6 overflow-auto">
            <!-- شريط البحث وإضافة طالب -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-primary-700 flex items-center">
                    <span class="ml-2">👨‍🎓</span>
                    إدارة الطلاب
                </h2>

                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- حقل البحث -->
                    <div class="relative">
                        <input type="text" placeholder="ابحث عن طالب..."
                               class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" />
                        <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
                    </div>

                    <!-- زر إضافة طالب -->
                    <button onclick="openModal()" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                        <span class="ml-1">+</span> إضافة طالب جديد
                    </button>
                </div>
            </div>

            <!-- جدول الطلاب -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-right text-sm text-gray-500">الاسم الكامل</th>
                                <th class="p-3 text-right text-sm text-gray-500">اسم الأب</th>
                                <th class="p-3 text-right text-sm text-gray-500">الصف الدراسي</th>
                                <th class="p-3 text-right text-sm text-gray-500">رقم الهاتف</th>
                                <th class="p-3 text-right text-sm text-gray-500">تاريخ التسجيل</th>
                                <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 text-sm font-medium">أحمد محمد</td>
                                <td class="p-3 text-sm">محمد علي</td>
                                <td class="p-3 text-sm">الصف الخامس</td>
                                <td class="p-3 text-sm">0501234567</td>
                                <td class="p-3 text-sm">2025-05-26</td>
                                <td class="p-3 flex items-center">
                                    <button class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">✏️ تعديل</button>
                                    <button class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">🗑️ حذف</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 text-sm font-medium">سارة خالد</td>
                                <td class="p-3 text-sm">خالد حسين</td>
                                <td class="p-3 text-sm">الصف السادس</td>
                                <td class="p-3 text-sm">0507654321</td>
                                <td class="p-3 text-sm">2025-05-20</td>
                                <td class="p-3 flex items-center">
                                    <button class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">✏️ تعديل</button>
                                    <button class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">🗑️ حذف</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة طالب جديد -->
    <div id="studentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
                <h3 class="text-lg font-bold text-primary-700">إضافة طالب جديد</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">✖</button>
            </div>
            <div class="p-6">
            <!-- فقط جزء الفورم بعد التعديل -->
<form method="POST" action="#" class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
            <input type="text" name="full_name" required
                   class="w-full border border-orange-300 rounded-lg px-4 py-2" />
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">اسم الأب</label>
            <input type="text" name="father_name" required
                   class="w-full border border-orange-300 rounded-lg px-4 py-2" />
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">الصف الدراسي</label>
            <select name="grade" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                <option value="">اختر الصف</option>
                <option value="الأول ابتدائي">الأول ابتدائي</option>
                <option value="الثاني ابتدائي">الثاني ابتدائي</option>
                <option value="الثالث ابتدائي">الثالث ابتدائي</option>
                <option value="الرابع ابتدائي">الرابع ابتدائي</option>
                <option value="الخامس ابتدائي">الخامس ابتدائي</option>
                <option value="السادس ابتدائي">السادس ابتدائي</option>
                <option value="الأول متوسط">الأول متوسط</option>
                <option value="الثاني متوسط">الثاني متوسط</option>
                <option value="الثالث متوسط">الثالث متوسط</option>
                <option value="الأول ثانوي">الأول ثانوي</option>
                <option value="الثاني ثانوي">الثاني ثانوي</option>
                <option value="الثالث ثانوي">الثالث ثانوي</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">تاريخ الميلاد</label>
            <input type="date" name="birth_date"
                   class="w-full border border-orange-300 rounded-lg px-4 py-2" />
        </div>
    </div>

    <div class="pt-4 flex justify-end space-x-3 space-x-reverse">
        <button type="button" onclick="closeModal()"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
            إلغاء
        </button>
        <button type="submit"
                class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
            حفظ الطالب
        </button>
    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('studentModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            document.getElementById('studentModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('studentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
