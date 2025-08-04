<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة التصنيفات</title>
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
        };
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
        @if(session('success'))
    <div id="flashMessage" class="fixed inset-0 flex items-center justify-center z-50">
        <div
            class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 text-lg font-medium rounded-lg shadow-xl transition-opacity duration-500 opacity-100"
            id="flashMessageContent">
            {{ session('success') }}
        </div>
    </div>

    <script>
        setTimeout(() => {
            const flash = document.getElementById('flashMessageContent');
            if (flash) {
                flash.style.opacity = '0';
                setTimeout(() => {
                    const wrapper = document.getElementById('flashMessage');
                    if (wrapper) wrapper.remove();
                }, 500);
            }
        }, 3000);
    </script>
@endif

        <!-- العنوان وزر الإضافة -->
        <div class="flex justify-between items-center mb-6 bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <span class="ml-2">📂</span> إدارة التصنيفات
            </h2>
            <button onclick="openModal('addModal')" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm">
                + إضافة تصنيف جديد
            </button>
        </div>

        <!-- ✅ نموذج البحث -->
        <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
            <div class="flex gap-2 items-center bg-white p-4 rounded shadow">
                <input type="text" name="search" placeholder="ابحث باسم التصنيف..." value="{{ request('search') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded text-sm">بحث</button>
            </div>
        </form>

        <!-- جدول التصنيفات -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full text-sm text-center">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">الاسم</th>
                    <th class="p-3">تاريخ الإضافة</th>
                    <th class="p-3">الإجراءات</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($categories as $index => $category)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">{{ $index + 1 }}</td>
                        <td class="p-3">{{ $category->name }}</td>
                        <td class="p-3">{{ $category->created_at->format('Y-m-d H:i') }}</td>
                        <td class="p-3 flex justify-center space-x-2 space-x-reverse">
<button
   onclick="openEditModal({{ $category->category_id }}, '{{ $category->name }}')"
    class="text-primary-600 hover:text-white border border-primary-500 hover:bg-primary-500 px-3 py-1 rounded-lg transition text-sm"
>
    ✏️ تعديل
</button>

                            <form method="POST" action="{{ route('categories.destroy', $category->category_id) }}">
                                @csrf @method('DELETE')
<button
    type="button"
    onclick="confirmDelete('{{ route('categories.destroy', $category->category_id) }}')"
    class="text-primary-600 hover:text-white border border-primary-500 hover:bg-primary-500 px-3 py-1 rounded-lg transition text-sm"
>
    🗑️ حذف
</button>

                            </form>
                        </td>
                    </tr>
                @empty
                  <tr>
    <td colspan="7" class="p-4 text-gray-500">
        @if(request()->has('search') && request('search') != '')
            لم يتم العثور على أي تصنيفات تطابق نتائج البحث.
        @else
            لا توجد تصنيفات متاحة حالياً.
        @endif
    </td>
</tr>

                @endforelse
                </tbody>
            </table>

            <!-- ✅ روابط الصفحات مع تمرير البحث -->
            <div class="p-4">
                {{ $categories->appends(['search' => request('search')])->links() }}
            </div>
        </div>
    </div>
</div>

<!-- 🟢 مودال الإضافة -->
<div id="addModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">إضافة تصنيف جديد</h3>
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 text-sm">اسم التصنيف</label>
                <input name="name" required class="w-full border px-3 py-2 rounded" />
            </div>
            <div class="flex justify-end space-x-2 space-x-reverse">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 border rounded">إلغاء</button>
                <button class="px-4 py-2 bg-primary-500 text-white rounded">حفظ</button>
            </div>
        </form>
    </div>
</div>

<!-- 🟠 مودال التعديل -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">تعديل التصنيف</h3>
        <form id="editForm" method="POST">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block mb-1 text-sm">اسم التصنيف</label>
                <input name="name" id="editName" required class="w-full border px-3 py-2 rounded" />
            </div>
            <div class="flex justify-end space-x-2 space-x-reverse">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 border rounded">إلغاء</button>
                <button class="px-4 py-2 bg-primary-500 text-white rounded">تحديث</button>
            </div>
        </form>
    </div>
</div>
<!-- 🔴 مودال تأكيد الحذف -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl w-full max-w-sm text-center">
        <h3 class="text-lg font-bold text-primary-700 mb-4">تأكيد الحذف</h3>
        <p class="text-gray-700 mb-6">هل أنت متأكد أنك تريد حذف هذا التصنيف؟</p>
        <form id="deleteForm" method="POST" class="flex justify-center gap-4">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('deleteModal')" class="px-4 py-2 rounded border">لا</button>
            <button class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded">نعم</button>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openEditModal(id, name, description) {
        document.getElementById('editName').value = name;
        document.getElementById('editForm').action = `/categories/${id}`;
        openModal('editModal');
    }

     function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function confirmDelete(actionUrl) {
        const form = document.getElementById('deleteForm');
        form.action = actionUrl;
        openModal('deleteModal');
    }
</script>
</body>
</html>
