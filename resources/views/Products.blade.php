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
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
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
                            <th class="p-3 text-sm text-gray-500">الصورة</th>
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
                            <td class="p-3 text-sm">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="صورة المنتج" class="product-image" />
                                @else
                                    —
                                @endif
                            </td>
                            <td class="p-3 text-sm font-medium">{{ $product->name }}</td>
                            <td class="p-3 text-sm">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</td>
                            <td class="p-3 text-sm">{{ number_format($product->price, 2) }} ر.س</td>
                            <td class="p-3 text-sm">{{ $product->quantity }}</td>
                            <td class="p-3">
                                <span class="{{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-3 py-1 rounded-full text-xs">
                                    {{ $product->is_active ? 'نشط' : 'غير نشط' }}
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

<!-- Modal لإضافة وتعديل المنتج -->
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

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الصنف</label>
                        <select name="category_id" id="productCategory" required
                            class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="" disabled selected>اختر الصنف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

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
                        <input type="number" name="price" id="productPrice" min="0" step="0.01" required
                               class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الكمية</label>
                        <input type="number" name="quantity" id="productQuantity" min="0" required
                               class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">الحالة</label>
                        <select name="is_active" id="productStatus" required
                                class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                        @error('is_active') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600 mb-1">صورة المنتج (اختياري)</label>
                        <input type="file" name="image" id="productImage" accept="image/*"
                               class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                    <button type="button" onclick="closeProductModal()" class="px-6 py-2 rounded-lg border border-gray-300 hover:border-gray-400 transition">
                        إلغاء
                    </button>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white transition">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // فتح مودال إضافة منتج جديد
    function openProductModal() {
        resetForm();
        document.getElementById('productModalTitle').textContent = 'إضافة منتج جديد';
        const form = document.getElementById('productForm');
        form.action = '{{ route('products.store') }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('productModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // فتح مودال تعديل منتج (تمرير البيانات من الزر)
    function openEditProductModal(product) {
        resetForm();
        document.getElementById('productModalTitle').textContent = 'تعديل المنتج';
        const form = document.getElementById('productForm');
        form.action = `/products/${product.product_id}`;
        document.getElementById('formMethod').value = 'PUT';

        document.getElementById('productCategory').value = product.category_id ?? '';
        document.getElementById('productName').value = product.name ?? '';
        document.getElementById('productDescription').value = product.description ?? '';
        document.getElementById('productPrice').value = product.price ?? '';
        document.getElementById('productQuantity').value = product.quantity ?? '';
        document.getElementById('productStatus').value = product.is_active ? '1' : '0';

        document.getElementById('productModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    // إغلاق المودال ومسح البيانات
    function closeProductModal() {
        document.getElementById('productModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        resetForm();
    }

    // إعادة تعيين النموذج إلى فارغ
    function resetForm() {
        const form = document.getElementById('productForm');
        form.reset();
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('productCategory').value = '';
    }
</script>

</body>
</html>
