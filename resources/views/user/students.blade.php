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

            <!-- رسالة النجاح عند إضافة طالب -->
    @if(session('success'))
        <div id="flashMessage"
             class="fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg animate-fade-in-out z-50"
             role="alert">
            {{ session('success') }}
        </div>
    @endif

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
                                <th class="p-3 text-right text-sm text-gray-500">تاريخ التسجيل</th>
                                <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                            </tr>
                        </thead>
                      <tbody class="divide-y divide-gray-200">
    @foreach($students as $student)
        <tr class="hover:bg-gray-50 transition">
            <td class="p-3 text-sm font-medium">{{ $student->full_name }}</td>
            <td class="p-3 text-sm">{{ $student->father_name }}</td>
            <td class="p-3 text-sm">{{ $student->class }}</td>
            <td class="p-3 text-sm">{{ $student->created_at->format('Y-m-d') }}</td>
            <td class="p-3 flex items-center">
                <button class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">✏️ تعديل</button>
                <button class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">🗑️ حذف</button>
            </td>
        </tr>
    @endforeach

    @if($students->isEmpty())
        <tr>
            <td colspan="5" class="p-4 text-center text-gray-500">لا يوجد طلاب مسجلين حالياً.</td>
        </tr>
    @endif
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
<form method="POST" action="{{ route('students.store') }}" class="space-y-4">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" required
                   class="w-full border border-orange-300 rounded-lg px-4 py-2" />
            @error('full_name')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">الصف الدراسي</label>
            <select name="class" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                <option value="">اختر الصف</option>
                <option value="الأول ابتدائي" {{ old('class') == 'الأول ابتدائي' ? 'selected' : '' }}>الأول ابتدائي</option>
                <option value="الثاني ابتدائي" {{ old('class') == 'الثاني ابتدائي' ? 'selected' : '' }}>الثاني ابتدائي</option>
                <option value="الثالث ابتدائي" {{ old('class') == 'الثالث ابتدائي' ? 'selected' : '' }}>الثالث ابتدائي</option>
                <option value="الرابع ابتدائي" {{ old('class') == 'الرابع ابتدائي' ? 'selected' : '' }}>الرابع ابتدائي</option>
                <option value="الخامس ابتدائي" {{ old('class') == 'الخامس ابتدائي' ? 'selected' : '' }}>الخامس ابتدائي</option>
                <option value="السادس ابتدائي" {{ old('class') == 'السادس ابتدائي' ? 'selected' : '' }}>السادس ابتدائي</option>
                <option value="الأول متوسط" {{ old('class') == 'الأول متوسط' ? 'selected' : '' }}>الأول متوسط</option>
                <option value="الثاني متوسط" {{ old('class') == 'الثاني متوسط' ? 'selected' : '' }}>الثاني متوسط</option>
                <option value="الثالث متوسط" {{ old('class') == 'الثالث متوسط' ? 'selected' : '' }}>الثالث متوسط</option>
                <option value="الأول ثانوي" {{ old('class') == 'الأول ثانوي' ? 'selected' : '' }}>الأول ثانوي</option>
                <option value="الثاني ثانوي" {{ old('class') == 'الثاني ثانوي' ? 'selected' : '' }}>الثاني ثانوي</option>
                <option value="الثالث ثانوي" {{ old('class') == 'الثالث ثانوي' ? 'selected' : '' }}>الثالث ثانوي</option>
            </select>
            @error('class')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">تاريخ الميلاد</label>
            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                   class="w-full border border-orange-300 rounded-lg px-4 py-2" />
            @error('birth_date')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- حقل اختيار ولي الأمر -->
        <div class="md:col-span-2">
            <label class="block text-sm text-gray-600 mb-1">ولي الأمر</label>
            <select name="parent_id" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                <option value="">اختر ولي الأمر</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->parent_id }}" {{ old('parent_id') == $parent->parent_id ? 'selected' : '' }}>
                        {{ $parent->user->full_name ?? 'ولي أمر #' . $parent->parent_id }}
                    </option>
                @endforeach
            </select>
            @error('parent_id')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
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

         window.addEventListener('DOMContentLoaded', () => {
        const flash = document.getElementById('flashMessage');
        if (flash) {
            setTimeout(() => {
                flash.style.display = 'none';
            }, 3000);
        }
    });
    </script>
</body>
</html>
