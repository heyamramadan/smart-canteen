<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>نظام المقصف الذكي</title>
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
</head>
<body class="bg-gray-50">

<!-- الهيكل العام -->
<div class="flex h-screen">
  <!-- الشريط الجانبي -->
  @include('layouts.sidebar')

  <!-- المحتوى الرئيسي -->
  <div class="flex-1 flex flex-col overflow-hidden">

    <!-- الصف العلوي: بيانات الطالب والمنتجات -->
    <div class="flex flex-col md:flex-row h-1/2">

      <!-- بيانات الطالب -->
      <div class="w-full md:w-1/2 bg-white p-4 border-l overflow-y-auto">
        <h2 class="text-xl font-bold text-primary mb-6 text-center">بيانات الطالب</h2>

        <!-- حقل البحث -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">بحث عن طالب</label>
          <input type="text" id="studentSearchInput" class="w-full p-2 border rounded mb-4" placeholder="ادخل اسم الطالب" autocomplete="off" />
        </div>

        <!-- نتائج البحث -->
        <div class="overflow-x-auto mb-6">
          <table class="w-full text-sm text-right border border-primary-100 rounded" id="studentsTable">
            <thead class="bg-primary-100 text-primary-700">
              <tr>
                <th class="p-2 border">اسم الطالب</th>
                <th class="p-2 border">اسم الأب</th>
                <th class="p-2 border">الصف</th>
              </tr>
            </thead>
            <tbody>
              <!-- النتائج ستُملأ بواسطة JavaScript -->
            </tbody>
          </table>
        </div>

        <!-- الطلبات السابقة -->
        <div class="border-t pt-4">
          <h3 class="font-semibold text-primary mb-2">طلبات سابقة</h3>
          <div class="space-y-2">
            <div class="p-2 border rounded hover:bg-gray-50 cursor-pointer">
              <div class="flex justify-between">
                <span>12/05/2023</span>
                <span class="font-medium">45.00 ر.س</span>
              </div>
              <div class="text-xs text-gray-500">2 منتجات</div>
            </div>
            <div class="p-2 border rounded hover:bg-gray-50 cursor-pointer">
              <div class="flex justify-between">
                <span>10/05/2023</span>
                <span class="font-medium">32.50 ر.س</span>
              </div>
              <div class="text-xs text-gray-500">3 منتجات</div>
            </div>
          </div>
        </div>
      </div>

      <!-- المنتجات -->
      <div class="w-full md:w-1/2 bg-white p-4 overflow-y-auto border-r flex flex-col">
        <h2 class="text-xl font-bold text-primary mb-6 text-center">المنتجات</h2>

        <!-- التصنيفات -->
        <div class="mb-4">
          <h3 class="font-semibold text-primary mb-2">التصنيفات</h3>
          <div id="categoriesContainer" class="flex flex-wrap gap-4">
            <!-- التصنيفات ديناميكية تظهر هنا -->
            <span class="text-gray-400">يرجى اختيار طالب أولاً</span>
          </div>
        </div>

        <!-- المنتجات المتاحة -->
        <div class="flex-1 overflow-y-auto">
          <h3 class="font-semibold text-primary mb-2">المنتجات المتاحة</h3>
          <table class="w-full border text-right">
            <thead>
              <tr class="bg-primary-100">
                <th class="p-2 border">المنتج</th>
                <th class="p-2 border">السعر</th>
                <th class="p-2 border">الكمية المتوفرة</th>
                <th class="p-2 border">الإجراء</th>
              </tr>
            </thead>
            <tbody id="productTable">
              <!-- المنتجات ديناميكية تظهر هنا -->
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- فاتورة البيع -->
    <div class="flex-1 p-4 bg-white border-t overflow-y-auto">
      <div class="max-w-4xl mx-auto">

        <!-- عنوان -->
        <h1 class="text-xl font-bold text-primary mb-4">فاتورة البيع</h1>

        <!-- جدول الفاتورة -->
        <div class="mb-4 overflow-x-auto">
          <table class="w-full border text-sm text-right" id="invoiceTable">
            <thead class="bg-primary-100 text-primary-700">
              <tr>
                <th class="p-2 border">المنتج</th>
                <th class="p-2 border">الكمية</th>
                <th class="p-2 border">سعر القطعة</th>
                <th class="p-2 border">الإجمالي</th>
                <th class="p-2 border">الإجراء</th>
              </tr>
            </thead>
            <tbody>
              <!-- الصفوف تتم إضافتها هنا ديناميكياً -->
            </tbody>
          </table>
        </div>

        <!-- الإجمالي -->
        <div class="bg-primary-50 text-black p-4 rounded-lg mb-4">
          <div class="flex justify-between font-bold text-lg">
            <span>إجمالي الفاتورة</span>
            <span id="totalAmount">0.00 ر.س</span>
          </div>
        </div>

        <!-- زر التأكيد -->
        <div class="text-center">
          <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg text-lg font-bold" onclick="confirmSale()">
            تأكيد عملية البيع
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const invoiceTableBody = document.querySelector('#invoiceTable tbody');
  const totalAmountSpan = document.getElementById('totalAmount');
  const categoriesContainer = document.getElementById('categoriesContainer');
  const productTableBody = document.getElementById('productTable');
  const studentSearchInput = document.getElementById('studentSearchInput');
  const studentsTableBody = document.querySelector('#studentsTable tbody');

  let invoiceItems = [];
  let allProducts = []; // المنتجات الكاملة بعد الجلب
  let currentStudentId = null;
  let currentCategory = null;

  // بحث الطلاب
  studentSearchInput.addEventListener('input', function () {
    const query = this.value.trim();

    if (query.length < 2) {
      studentsTableBody.innerHTML = '';
      categoriesContainer.innerHTML = `<span class="text-gray-400">يرجى اختيار طالب أولاً</span>`;
      productTableBody.innerHTML = '';
      currentStudentId = null;
      allProducts = [];
      invoiceItems = [];
      renderInvoice();
      return;
    }

    fetch(`/students/search?query=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        studentsTableBody.innerHTML = '';
        data.forEach(student => {
          const tr = document.createElement('tr');
          tr.classList.add('cursor-pointer', 'hover:bg-primary-50');
          tr.innerHTML = `
            <td class="p-2 border">${student.full_name}</td>
            <td class="p-2 border">${student.father_name ?? '—'}</td>
            <td class="p-2 border">${student.class}</td>
          `;
          // عند اختيار طالب
          tr.addEventListener('click', () => {
            currentStudentId = student.student_id;
            loadCategoriesAndProducts(currentStudentId);
            highlightSelectedStudent(tr);
            invoiceItems = [];
            renderInvoice();
          });
          studentsTableBody.appendChild(tr);
        });
      }).catch(() => {
        studentsTableBody.innerHTML = `<tr><td colspan="3" class="text-center p-2 text-red-500">خطأ في جلب الطلاب</td></tr>`;
      });
  });

  function highlightSelectedStudent(selectedRow) {
    // إزالة تمييز من الكل
    studentsTableBody.querySelectorAll('tr').forEach(r => {
      r.classList.remove('bg-primary-100', 'text-white');
    });
    // تمييز الصف المحدد
    selectedRow.classList.add('bg-primary-100', 'text-white');
  }

  // جلب التصنيفات والمنتجات للطالب المحدد
  function loadCategoriesAndProducts(studentId) {
    fetch(`/students/${studentId}/allowed-categories`)
      .then(res => res.json())
      .then(data => {
        renderCategories(data.categories);
        allProducts = data.products;
        currentCategory = null;
        renderProducts(allProducts);
      })
      .catch(() => {
        categoriesContainer.innerHTML = `<span class="text-red-500">خطأ في جلب التصنيفات والمنتجات</span>`;
        productTableBody.innerHTML = '';
      });
  }

  // عرض التصنيفات
  function renderCategories(categories) {
    if (!categories.length) {
      categoriesContainer.innerHTML = `<span class="text-gray-400">لا توجد تصنيفات متاحة لهذا الطالب</span>`;
      productTableBody.innerHTML = '';
      return;
    }
    categoriesContainer.innerHTML = '';
    categories.forEach(cat => {
      const label = document.createElement('label');
      label.className = "cursor-pointer select-none";
      label.innerHTML = `
        <input type="radio" name="category" value="${cat.name}" class="hidden" onchange="onCategoryChange(this.value)">
        <span class="px-3 py-1 border rounded hover:bg-primary-50">${cat.name}</span>
      `;
      categoriesContainer.appendChild(label);
    });
  }

  function onCategoryChange(category) {
    currentCategory = category;
    renderProducts(allProducts);
  }

  // عرض المنتجات مع فلترة
  function renderProducts(products) {
    let filtered = currentCategory ? products.filter(p => p.category_name === currentCategory) : products;

    if (!filtered.length) {
      productTableBody.innerHTML = `<tr><td colspan="4" class="p-2 border text-center text-gray-500">لا توجد منتجات في هذا التصنيف</td></tr>`;
      return;
    }

    productTableBody.innerHTML = '';
    filtered.forEach(product => {
      const tr = document.createElement('tr');
      tr.dataset.category = product.category_name;
      tr.innerHTML = `
        <td class="p-2 border">${product.name}</td>
        <td class="p-2 border">${product.price.toFixed(2)} ر.س</td>
        <td class="p-2 border text-center">${product.quantity}</td>
        <td class="p-2 border text-center">
          <button onclick="addToInvoice(${product.id}, '${product.name}', ${product.price}, ${product.quantity})" class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700" ${product.quantity <= 0 ? 'disabled class="opacity-50 cursor-not-allowed"' : ''}>+</button>
        </td>
      `;
      productTableBody.appendChild(tr);
    });
  }

  // إضافة منتج إلى الفاتورة
  // تمرير id لضمان التفريق بين المنتجات
  function addToInvoice(id, name, price, availableQty) {
    const existingItem = invoiceItems.find(i => i.id === id);
    if (existingItem) {
      if (existingItem.quantity < availableQty) {
        existingItem.quantity++;
      } else {
        alert('الكمية المطلوبة أكبر من المتوفر.');
      }
    } else {
      if (availableQty > 0) {
        invoiceItems.push({ id, name, price, quantity: 1 });
      } else {
        alert('المنتج غير متوفر حالياً.');
      }
    }
    renderInvoice();
  }

  // إزالة عنصر من الفاتورة
  function removeItem(index) {
    invoiceItems.splice(index, 1);
    renderInvoice();
  }

  // تحديث كمية منتج في الفاتورة
  function updateQuantity(index, delta) {
    const item = invoiceItems[index];
    const product = allProducts.find(p => p.id === item.id);

    if (!product) return;

    let newQty = item.quantity + delta;
    if (newQty > product.quantity) {
      alert('لا يمكن زيادة الكمية عن المتوفر.');
      return;
    } else if (newQty <= 0) {
      removeItem(index);
    } else {
      item.quantity = newQty;
      renderInvoice();
    }
  }

  // عرض الفاتورة
  function renderInvoice() {
    invoiceTableBody.innerHTML = '';
    let total = 0;
    invoiceItems.forEach((item, i) => {
      const itemTotal = item.price * item.quantity;
      total += itemTotal;

      const row = document.createElement('tr');
      row.innerHTML = `
        <td class="p-2 border">${item.name}</td>
        <td class="p-2 border text-center">
          <button onclick="updateQuantity(${i}, -1)" class="bg-primary-500 text-white px-2 rounded">-</button>
          <span class="mx-2">${item.quantity}</span>
          <button onclick="updateQuantity(${i}, 1)" class="bg-primary-500 text-white px-2 rounded">+</button>
        </td>
        <td class="p-2 border text-center">${item.price.toFixed(2)} ر.س</td>
        <td class="p-2 border text-center">${itemTotal.toFixed(2)} ر.س</td>
        <td class="p-2 border text-center">
          <button onclick="removeItem(${i})" class="text-red-600 font-bold">إزالة</button>
        </td>
      `;
      invoiceTableBody.appendChild(row);
    });
    totalAmountSpan.textContent = `${total.toFixed(2)} ر.س`;
  }

  // تأكيد البيع (يمكن تعديلها حسب نظامك)
  function confirmSale() {
    if (!currentStudentId) {
      alert('يرجى اختيار طالب أولاً.');
      return;
    }
    if (invoiceItems.length === 0) {
      alert('لا يوجد منتجات في الفاتورة.');
      return;
    }

    // تحضير بيانات الفاتورة للإرسال (مثال)
    const saleData = {
      student_id: currentStudentId,
      items: invoiceItems.map(i => ({ product_id: i.id, quantity: i.quantity })),
    };

    // إرسال البيانات للسيرفر (مثال)
    fetch('/sales/confirm', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(saleData),
    })
    .then(res => {
      if (!res.ok) throw new Error('حدث خطأ في تأكيد البيع');
      return res.json();
    })
    .then(data => {
      alert('تم تأكيد عملية البيع بنجاح');
      // إعادة تهيئة
      invoiceItems = [];
      renderInvoice();
      // ممكن إعادة تحميل المنتجات لتحديث الكميات
      loadCategoriesAndProducts(currentStudentId);
    })
    .catch(err => {
      alert(err.message || 'حدث خطأ أثناء تأكيد البيع');
    });
  }
</script>

</body>
</html>
