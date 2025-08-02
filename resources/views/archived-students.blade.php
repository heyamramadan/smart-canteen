
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>أرشيف الطلاب - لوحة تحكم المقصف</title>
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
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
                <h2 class="text-lg font-bold text-primary-700 flex items-center">
                    <span class="ml-2">📦</span> أرشيف الطلاب
                </h2>
                 <div class="relative mb-4 w-full max-w-xs">
    <input type="text" id="archivedSearchInput" placeholder="ابحث عن طالب..."
        class="pr-10 pl-4 py-2 border rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" />
    <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
</div>

            </div>


@if(session('success'))
<div id="successAlert"
    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-2xl z-50 text-center text-lg font-medium transition-opacity duration-300">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div id="errorAlert"
    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-2xl z-50 text-center text-lg font-medium transition-opacity duration-300">
    {{ session('error') }}
</div>
@endif

            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-right text-sm text-gray-500">الصورة</th>
                                <th class="p-3 text-right text-sm text-gray-500">الاسم الكامل</th>
                                <th class="p-3 text-right text-sm text-gray-500">اسم الأب</th>
                                <th class="p-3 text-right text-sm text-gray-500">الصف الدراسي</th>
                                <th class="p-3 text-right text-sm text-gray-500">تاريخ التسجيل</th>
                                 <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($students as $student)
                                 <tr class="transition bg-gray-100 text-gray-400 hover:bg-gray-200">
                                    <td class="p-3">
                                        @if($student->image_path && file_exists(public_path('storage/' . $student->image_path)))
                                            <img src="{{ asset('storage/' . $student->image_path) }}" class="h-10 w-10 rounded-full object-cover" />
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-xs">لا يوجد</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3 text-sm font-medium">{{ $student->full_name }}</td>
                                    <td class="p-3 text-sm">{{ $student->user->full_name ?? 'غير معروف' }}</td>
                                    <td class="p-3 text-sm">{{ $student->class }}</td>
                                    <td class="p-3 text-sm">{{ $student->created_at->format('Y-m-d') }}</td>
                                    <td class="p-3">
<form method="POST" action="{{ route('archived-students.restore', $student->student_id) }}" class="restoreForm">

                                            @csrf
                                            <button type="button" onclick="confirmRestore(this)"
                                                class="bg-white text-orange-500 border border-orange-500 px-3 py-1 rounded-lg hover:bg-orange-500 hover:text-white transition flex items-center space-x-1 space-x-reverse">
                                                ♻️ <span>استعادة</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-4 text-center text-gray-500">لا يوجد طلاب مؤرشفين حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-4 py-2">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal التأكيد -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white border border-gray-200 text-gray-700 rounded-xl shadow-lg w-full max-w-md p-6 text-center absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
            <h3 id="confirmModalTitle" class="text-lg font-bold mb-4">تأكيد الإجراء</h3>
            <p id="confirmModalMessage" class="text-sm text-gray-600 mb-6">سيتم تنفيذ هذا الإجراء.</p>
            <div class="flex justify-center gap-4">
                <button id="confirmYesBtn" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition">نعم</button>
                <button onclick="closeConfirmModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">إلغاء</button>
            </div>
        </div>
    </div>

    <script>
        let confirmAction = null;

        function showConfirmModal(title, message, onConfirm) {
            document.getElementById('confirmModalTitle').textContent = title;
            document.getElementById('confirmModalMessage').textContent = message;
            document.getElementById('confirmModal').classList.remove('hidden');
            confirmAction = onConfirm;
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            confirmAction = null;
        }

        document.getElementById('confirmYesBtn').addEventListener('click', () => {
            if (typeof confirmAction === 'function') {
                confirmAction();
            }
            closeConfirmModal();
        });

        function confirmRestore(button) {
            const form = button.closest('form');
            showConfirmModal('تأكيد الاستعادة', 'هل تريد استعادة هذا الطالب؟', () => form.submit());
        }
            setTimeout(() => {
        const alert = document.getElementById('successAlert');
        if (alert) {
            alert.classList.add('opacity-0');
            setTimeout(() => alert.remove(), 300); // يمكن حذفه إن أردت فقط الإخفاء
        }
    }, 3000);
        document.getElementById('archivedSearchInput').addEventListener('input', function (e) {
        const query = e.target.value.trim();

        fetch(`/archived-students/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => updateArchivedTable(data))
            .catch(error => console.error('Error:', error));
    });

    function updateArchivedTable(students) {
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
            row.className = 'transition bg-gray-100 text-gray-400 hover:bg-gray-200';

            row.innerHTML = `
                <td class="p-3">
                    ${student.image_path ?
                        `<img src="/storage/${student.image_path}" class="h-10 w-10 rounded-full object-cover" />` :
                        `<div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500 text-xs">لا يوجد</span>
                        </div>`
                    }
                </td>
                <td class="p-3 text-sm font-medium">${student.full_name}</td>
                <td class="p-3 text-sm">${student.user?.full_name || 'غير معروف'}</td>
                <td class="p-3 text-sm">${student.class}</td>
                <td class="p-3 text-sm">${new Date(student.created_at).toLocaleDateString()}</td>
                <td class="p-3">
                    <form method="POST" action="/archived-students/${student.student_id}/restore" class="restoreForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="button" onclick="confirmRestore(this)"
                            class="bg-white text-orange-500 border border-orange-500 px-3 py-1 rounded-lg hover:bg-orange-500 hover:text-white transition flex items-center space-x-1 space-x-reverse">
                            ♻️ <span>استعادة</span>
                        </button>
                    </form>
                </td>
            `;
            tbody.appendChild(row);
        });
    }
    setTimeout(() => {
    const alert = document.getElementById('errorAlert');
    if (alert) {
        alert.classList.add('opacity-0');
        setTimeout(() => alert.remove(), 300);
    }
}, 3000);

    </script>
</body>
</html>
