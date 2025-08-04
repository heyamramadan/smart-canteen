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

    @if (session('success'))
  <div id="flashMessage" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-green-100 border border-green-400 text-green-700 px-8 py-4 rounded-xl shadow-xl text-xl font-bold text-center animate-fade-in-out">
        {{ session('success') }}
    </div>
</div>

@endif

    <!-- محتوى إدارة المنتجات -->
    <div class="flex-1 p-6 overflow-auto">
        <!-- شريط البحث وإضافة منتج -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <span class="ml-2">🛒</span>
                إدارة المنتجات
            </h2>

            <div class="flex items-center space-x-4 space-x-reverse">
                <!-- حقل البحث -->
                <form method="GET" action="{{ route('products.index') }}" class="relative">
                  <input
    type="text"
    id="liveSearch"
    name="search"
    placeholder="ابحث عن منتج..."
    class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
/>

                </form>

                <!-- زر إضافة منتج -->
                <button onclick="openProductModal()" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                    <span class="ml-1">+</span>
                    إضافة منتج جديد
                </button>
            </div>
        </div>

        <!-- جدول المنتجات -->
        <div id="productTableContainer">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-right">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-sm text-gray-500">#</th>

                            <th class="p-3 text-sm text-gray-500">الصورة</th>
                            <th class="p-3 text-sm text-gray-500">اسم المنتج</th>
                            <th class="p-3 text-sm text-gray-500">الوصف</th>
                            <th class="p-3 text-sm text-gray-500">السعر</th>
                            <th class="p-3 text-sm text-gray-500">الكمية</th>
                             <th class="p-3 text-sm text-gray-500">الحالة</th>
                               <th class="p-3 text-sm text-gray-500">تاريخ الإنشاء</th>
                            <th class="p-3 text-sm text-gray-500">تاريخ الصلاحية</th>

                            <th class="p-3 text-sm text-gray-500">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                      @forelse($products as $index => $product)

                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 text-sm ">{{ $index + 1 }}</td>

                            <td class="p-3 text-sm">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="صورة المنتج" class="product-image" />
                                @else
                                    —
                                @endif
                            </td>
                            <td class="p-3 text-sm font-medium">{{ $product->name }}</td>
                            <td class="p-3 text-sm">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</td>
                            <td class="p-3 text-sm">{{ number_format($product->price, 2) }} د.ل</td>
                            <td class="p-3 text-sm">{{ $product->quantity }}</td>
                            <td class="p-3">
                                <span class="{{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-3 py-1 rounded-full text-xs">
                                    {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="p-3 text-sm">{{ $product->created_at->format('Y-m-d') }}</td>
                            <td class="p-3 text-sm">
    @php
        $exp = \Carbon\Carbon::parse($product->expiry_date);
        $daysLeft = now()->diffInDays($exp, false);
    @endphp

    <span class="{{ $daysLeft <= 7 ? 'text-red-600 font-bold' : '' }}">
        {{ $product->expiry_date }}
        @if ($daysLeft <= 7 && $daysLeft >= 0)
            <span class="text-xs text-red-500 block">⚠ {{ $daysLeft }} يوم متبقي</span>
        @elseif ($daysLeft < 0)
            <span class="text-xs text-red-500 block">⛔ منتهي</span>
        @endif
    </span>
</td>

                            <td class="p-3 flex items-center space-x-1 space-x-reverse">
 <button
    class="text-primary-600 hover:text-white border border-primary-500 hover:bg-primary-500 px-3 py-1 rounded-lg transition text-sm edit-btn"
    data-product='@json($product)'
>
    ✏️ تعديل
</button>


                                <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                    @csrf
                                    @method('DELETE')
<button
    type="button"
    onclick="showDeleteModal({{ $product->product_id }})"
    class="text-primary-600 hover:text-white border border-primary-500 hover:bg-primary-500 px-3 py-1 rounded-lg transition text-sm"
>
    🗑️ حذف
</button>



                                </form>
                            </td>
                        </tr>
                          @empty
      <tr>
          <td colspan="10" class="p-4 text-center text-gray-500">لا توجد منتجات مطابقة للبحث.</td>
      </tr>
                         @endforelse
                    </tbody>
                </table>
            </div>
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
<textarea name="description" id="productDescription" rows="3" required
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
                    <input type="hidden" name="is_active" id="productStatus" value="1">

<div>
    <label class="block text-sm text-gray-600 mb-1">تاريخ الصلاحية</label>
    <input type="date" name="expiry_date" id="productExpiry" required
           class="w-full border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
    @error('expiry_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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
<!-- مودال تأكيد الحذف -->
<div id="deleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md mx-auto">
        <div class="p-6">
      <h2 class="text-lg font-bold text-primary-700 mb-4">⚠️ تأكيد الحذف</h2>

            <p class="text-sm text-gray-700 mb-6">هل أنت متأكد من حذف هذا المنتج؟.</p>

            <div class="flex justify-end space-x-3 space-x-reverse">
                <button onclick="closeDeleteModal()" class="px-4 py-2 rounded-lg border border-gray-300 hover:border-gray-400 transition">
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
<button type="submit" class="px-4 py-2 rounded-lg bg-primary-500 hover:bg-primary-600 text-white transition">
    نعم، احذف
</button>


                </form>
            </div>
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
        document.getElementById('productExpiry').value = product.expiry_date ?? '';

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

    let debounceTimer;

    document.getElementById('liveSearch').addEventListener('input', function () {
        clearTimeout(debounceTimer); // إلغاء أي مؤقت سابق
        const query = this.value;

        // تأخير التنفيذ قليلاً لتحسين الأداء
        debounceTimer = setTimeout(() => {
            fetch(`{{ route('products.index') }}?search=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // لتحديد أنه طلب AJAX
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('#productTableContainer');
                document.getElementById('productTableContainer').innerHTML = newTable.innerHTML;
                  bindEditButtons();
    bindDeleteButtons();
            });
        }, 300); // 300 ميلي ثانية تأخير
    });

document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function () {
        const product = JSON.parse(this.dataset.product);
        openEditProductModal(product);
    });
});
function showDeleteModal(productId) {
    const form = document.getElementById('deleteForm');
    form.action = `/products/${productId}`; // تأكد أن المسار صحيح حسب الـ Route الخاص بك
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}
function bindEditButtons() {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const product = JSON.parse(this.dataset.product);
            openEditProductModal(product);
        });
    });
}

function bindDeleteButtons() {
    document.querySelectorAll('[onclick^="showDeleteModal"]').forEach(button => {
        const id = button.getAttribute('onclick').match(/\d+/)?.[0];
        button.addEventListener('click', function () {
            showDeleteModal(id);
        });
    });
}
document.addEventListener('DOMContentLoaded', function () {
    bindEditButtons();
    bindDeleteButtons();
});
document.addEventListener('DOMContentLoaded', function () {
    const flash = document.getElementById('flashMessage');
    if (flash) {
        setTimeout(() => {
            flash.classList.add('fade-out');
            setTimeout(() => flash.remove(), 1000); // إزالة العنصر بعد اختفائه
        }, 4000); // بعد 4 ثوانٍ
    }
});
    document.getElementById('productQuantity').addEventListener('input', function () {
        const quantity = parseInt(this.value);
        const status = document.getElementById('productStatus');
        if (!isNaN(quantity)) {
            status.value = quantity > 0 ? '1' : '0';
        }
    });
</script>

</body>
</html>

