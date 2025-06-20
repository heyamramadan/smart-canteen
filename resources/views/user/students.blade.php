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

        <!-- رسالة النجاح -->
        @if(session('success'))
            <div id="flashMessage" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-xl animate-fade-in-out transition-opacity duration-300">
                    {{ session('success') }}
                </div>
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
    <input type="text" id="searchInput" placeholder="ابحث عن طالب..."
           class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" />
    <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
</div>
                    <!-- زر إضافة طالب -->
                    <button onclick="openAddModal()" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
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
                                <th class="p-3 text-right text-sm text-gray-500">تاريخ الميلاد</th>
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
                                    <td class="p-3 text-sm">{{ $student->birth_date ? $student->birth_date->format('Y-m-d') : 'غير محدد' }}</td>
                                    <td class="p-3 text-sm">{{ $student->created_at->format('Y-m-d') }}</td>
                                    <td class="p-3 flex items-center">
                                        <button onclick="openEditModal(
                                            '{{ $student->student_id }}',
                                            '{{ $student->full_name }}',
                                            '{{ $student->father_name }}',
                                            '{{ $student->class }}',
                                            '{{ $student->birth_date ? $student->birth_date->format('Y-m-d') : '' }}',
                                            '{{ $student->parent_id }}'
                                        )" class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">
                                            ✏️ تعديل
                                        </button>
<form method="POST" action="{{ route('students.destroy', $student->student_id) }}" onsubmit="return confirm('هل أنت متأكد من أرشفة هذا الطالب؟');">

                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">
                                                🗑️ أرشفة
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if($students->isEmpty())
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">لا يوجد طلاب مسجلين حالياً.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة طالب جديد -->
    <div id="addStudentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
                <h3 class="text-lg font-bold text-primary-700">إضافة طالب جديد</h3>
                <button onclick="closeAddModal()" class="text-gray-500 hover:text-gray-700">✖</button>
            </div>
            <div class="p-6">
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
                        <button type="button" onclick="closeAddModal()"
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

    <!-- Modal لتعديل طالب -->
    <div id="editStudentModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
                <h3 class="text-lg font-bold text-primary-700">تعديل بيانات الطالب</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">✖</button>
            </div>
            <div class="p-6">
                <form method="POST" id="editStudentForm" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="student_id" id="edit_student_id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
                            <input type="text" name="full_name" id="edit_full_name" required
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">الصف الدراسي</label>
                            <select name="class" id="edit_class" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
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
                            <input type="date" name="birth_date" id="edit_birth_date"
                                   class="w-full border border-orange-300 rounded-lg px-4 py-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-600 mb-1">ولي الأمر</label>
                            <select name="parent_id" id="edit_parent_id" required class="w-full border border-orange-300 rounded-lg px-4 py-2">
                                <option value="">اختر ولي الأمر</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->parent_id }}">
                                        {{ $parent->user->full_name ?? 'ولي أمر #' . $parent->parent_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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

    <script>
        // وظائف إضافة طالب
        function openAddModal() {
            document.getElementById('addStudentModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAddModal() {
            document.getElementById('addStudentModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // وظائف تعديل طالب
        function openEditModal(id, fullName, fatherName, classVal, birthDate, parentId) {
            document.getElementById('edit_student_id').value = id;
            document.getElementById('edit_full_name').value = fullName;
            document.getElementById('edit_class').value = classVal;
            document.getElementById('edit_birth_date').value = birthDate;
            document.getElementById('edit_parent_id').value = parentId;

            // تحديث مسار الفورم
            document.getElementById('editStudentForm').action = '/students/' + id;

            document.getElementById('editStudentModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditModal() {
            document.getElementById('editStudentModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // إغلاق المودال عند النقر خارج المحتوى
        document.getElementById('addStudentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        document.getElementById('editStudentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // إخفاء رسالة النجاح بعد 3 ثوانٍ
        setTimeout(() => {
            const msg = document.getElementById('flashMessage');
            if (msg) msg.remove();
        }, 3000);

        // فتح المودال تلقائيًا إذا كان هناك أخطاء في التحقق
        @if ($errors->any())
            openAddModal();
        @endif
    </script>
    <script>
    // البحث عن الطلاب أثناء الكتابة
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.trim();

        if (query.length > 0) {
            fetch(`/students/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    updateStudentsTable(data);
                })
                .catch(error => console.error('Error:', error));
        } else {
            // إذا كان حقل البحث فارغاً، أعد عرض كل الطلاب
            fetch(`/students/search`)
                .then(response => response.json())
                .then(data => {
                    updateStudentsTable(data);
                });
        }
    });

    // تحديث جدول الطلاب بالنتائج
    function updateStudentsTable(students) {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        if (students.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">لا يوجد نتائج مطابقة للبحث.</td>
                </tr>
            `;
            return;
        }

        students.forEach(student => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition';
            row.innerHTML = `
                <td class="p-3 text-sm font-medium">${student.full_name}</td>
                <td class="p-3 text-sm">${student.father_name}</td>
                <td class="p-3 text-sm">${student.class}</td>
                <td class="p-3 text-sm">${student.birth_date ? student.birth_date : 'غير محدد'}</td>
                <td class="p-3 text-sm">${new Date(student.created_at).toLocaleDateString()}</td>
                <td class="p-3 flex items-center">
                    <button onclick="openEditModal(
                        '${student.student_id}',
                        '${student.full_name}',
                        '${student.father_name}',
                        '${student.class}',
                        '${student.birth_date ? student.birth_date : ''}',
                        '${student.parent_id}'
                    )" class="text-primary-500 hover:text-primary-700 mx-1 p-1 rounded hover:bg-primary-100 transition">
                        ✏️ تعديل
                    </button>
                    <form method="POST" action="/students/${student.student_id}" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">
                            🗑️ أرشفة
                        </button>
                    </form>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
</script>
</body>
</html>
