<!-- resources/views/students/archived.blade.php -->
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
            </div>

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
                                        <form method="POST" action="{{ route('students.restore', $student->student_id) }}" class="restoreForm">
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
    </script>
</body>
</html>
