<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إدارة المنتجات - لوحة تحكم المقصف</title>
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

    <!-- محتوى إدارة المنتجات -->
    <div class="flex-1 p-6 overflow-auto">
        <!-- شريط البحث وإضافة منتج -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <span class="ml-2">📂</span>
                إدارة المنتجات
            </h2>

            <div class="flex items-center space-x-4 space-x-reverse">
                <!-- حقل البحث -->
                <form method="GET" action="{{ route('products.index') }}" class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="ابحث عن منتج..."
                        class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    />
                    <button type="submit" class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
                        🔍
                    </button>
                </form>

                <!-- زر إضافة منتج -->
                <button onclick="openProductModal()" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                    <span class="ml-1">+</span>
                    إضافة منتج جديد
                </button>
            </div>
        </div>

        <!-- جدول المنتجات -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-sm text-gray-500">اسم المنتج</th>
                            <th class="p-3 text-sm text-gray-500">الوصف</th>
                            <th class="p-3 text-sm text-gray-500">السعر</th>
                            <th class="p-3 text-sm text-gray-500">الكمية</th>
                            <th class="p-3 text-sm text-gray-500">الحالة</th>
                            <th class="p-3 text-sm text-gray-500">تاريخ الإنشاء</th>
                            <th class="p-3 text-sm text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 text-sm font-medium">{{ $product->name }}</td>
                            <td class="p-3 text-sm">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</td>
                            <td class="p-3 text-sm">{{ $product->price }} ر.س</td>
                            <td class="p-3 text-sm">{{ $product->quantity }}</td>
                            <td class="p-3">
                                <span class="{{ $product->status == 'نشط' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-3 py-1 rounded-full text-xs">
                                    {{ $product->status }}
                                </span>
                            </td>
                            <td class="p-3 text-sm">{{ $product->created_at->format('Y-m-d') }}</td>
                            <td class="p-3 flex items-center space-x-1 space-x-reverse">
                                <button
                                    onclick="openEditProductModal(@json($product))"
                                    class="text-primary-500 hover:text-primary-700 p-1 rounded hover:bg-primary-100 transition"
                                >
                                    ✏️ تعديل
                                </button>
                                <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-100 transition">
                                        🗑️ حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding new product -->
<div id="productModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b p-4 flex justify-between items-center z-10">
            <h3 id="productModalTitle" class="text-lg font-bold text-primary-700">إضافة منتج جديد</h3>
            <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700">✖</button>
        </div>
        <div class="p-6">
            <form id="productForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">اسم المنتج</label>
                        <input type="text" name="name" id="productName" required
                               class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">وصف المنتج</label>
                        <textarea name="description" id="productDescription" rows="3"
                                  class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">السعر (ر.س)</label>
                        <input type="number" name="price" id="productPrice" step="0.01" required
                               class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الكمية المتاحة</label>
                        <input type="number" name="quantity" id="productQuantity" required
                               class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">حالة المنتج</label>
                        <select name="status" id="productStatus" required
                                class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="نشط">نشط</option>
                            <option value="غير نشط">غير نشط</option>
                        </select>
                        @error('status') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">صورة المنتج</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeProductModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        إلغاء
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                        حفظ المنتج
                    </button>
                </div>
            </form>
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
    // فتح مودال الإضافة
    function openProductModal() {
        resetForm();
        document.getElementById('productModalTitle').textContent = 'إضافة منتج جديد';
        document.getElementById('productForm').action = "{{ route('products.store') }}";
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('productModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // فتح مودال التعديل مع تعبئة البيانات
    function openEditProductModal(product) {
        resetForm();
        document.getElementById('productModalTitle').textContent = 'تعديل المنتج';
        document.getElementById('productForm').action = `/products/${product.id}`;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('productName').value = product.name ?? '';
        document.getElementById('productDescription').value = product.description ?? '';
        document.getElementById('productPrice').value = product.price ?? '';
        document.getElementById('productQuantity').value = product.quantity ?? '';
        document.getElementById('productStatus').value = product.status ?? 'نشط';

        document.getElementById('productModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // إغلاق المودال
    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // إعادة تعيين نموذج المودال (تفريغ الحقول)
    function resetForm() {
        const form = document.getElementById('productForm');
        form.reset();
        form.querySelectorAll('input[type="hidden"][name="_method"]').forEach(i => i.value = 'POST');
    }

    // إغلاق المودال عند النقر على الخلفية
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
     closeProductModal();
        }
    });

    // إخفاء رسالة الفلاش تلقائيًا بعد 3 ثوانٍ
    setTimeout(() => {
        const msg = document.getElementById('flashMessage');
        if (msg) msg.style.display = 'none';
    }, 3000);
</script>

</body>
</html>
