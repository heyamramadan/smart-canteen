<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>نظام المقصف الذكي</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#FFF7ED',
              100: '#FFEDD5',
              200: '#FED7AA',
              300: '#FDBA74',
              400: '#FB923C',
              500: '#F97316',
              600: '#EA580C',
              700: '#C2410C',
              800: '#9A3412',
              900: '#7C2D12',
            },
            secondary: {
              50: '#F8FAFC',
              100: '#F1F5F9',
              200: '#E2E8F0',
              300: '#CBD5E1',
              400: '#94A3B8',
              500: '#64748B',
              600: '#475569',
              700: '#334155',
              800: '#1E293B',
              900: '#0F172A',
            }
          },
          boxShadow: {
            'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
            'button': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
          },
          borderRadius: {
            'xl': '12px',
            '2xl': '16px',
          }
        }
      }
    }
  </script>
  <style>
    /* تحسينات عامة */
    body {
      font-family: 'Tajawal', sans-serif;
    }

    /* تحسين الجداول */
    table {
      border-collapse: separate;
      border-spacing: 0;
    }

    th, td {
      padding: 12px 16px;
      border: 1px solid #e2e8f0;
    }

    th {
      background-color: #F8FAFC;
      font-weight: 600;
      color: #334155;
    }

    tr:hover {
      background-color: #F8FAFC;
    }

    /* تحسين الأزرار */
    button {
      transition: all 0.2s ease;
    }

    button:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* تحسين كروت العناصر */
    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    .card:hover {
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    /* تحسين حقول الإدخال */
    input {
      transition: all 0.2s ease;
      border: 1px solid #CBD5E1;
    }

    input:focus {
      border-color: #F97316;
      box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }

    /* تحسين شريط التمرير */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #F1F5F9;
    }

    ::-webkit-scrollbar-thumb {
      background: #CBD5E1;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #94A3B8;
    }
  </style>
</head>
<body class="bg-gray-50">
<!-- الهيكل العام -->
<div class="flex h-screen">
  <!-- الشريط الجانبي -->
  @include('layouts.sidebar')

   <!-- المحتوى الرئيسي مع إمكانية التمرير -->
  <div class="flex-1 flex flex-col overflow-hidden">

   <!-- قسم علوي ثابت (الطلاب والمنتجات) -->
    <div class="flex flex-col lg:flex-row h-[55vh] min-h-[400px] p-4 gap-4">
      <!-- بيانات الطالب -->

      <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-sm flex flex-col overflow-hidden">
        <h2 class="text-2xl font-bold text-primary-700 mb-6 pb-3 border-b border-gray-100">بيانات الطالب</h2>

        <!-- حقل البحث -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">بحث عن طالب</label>
          <div class="flex gap-2">
            <input type="text" id="studentSearchInput" class="flex-1 p-3 border rounded-lg focus:ring-2 focus:ring-primary-300 focus:border-primary-400" placeholder="ادخل اسم الطالب أو امسح QR Code" autocomplete="off" />
            <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-3 rounded-lg shadow-button flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              بحث
            </button>
          </div>
          <p class="text-sm text-gray-500 mt-1">يمكنك استخدام قارئ QR Code الخارجي لمسح كود الطالب</p>
        </div>

        <div id="studentInfoContainer" class="mb-6"></div>

   <!-- نتائج البحث -->
<div class="flex-1 overflow-hidden flex flex-col mt-[-12px]">
<h3 class="font-semibold text-gray-700 mb-2">نتائج البحث</h3>
  <div class="border border-gray-200 rounded-lg overflow-hidden flex-1">
    <div class="overflow-y-auto h-full">
      <table class="w-full text-right min-w-full" id="studentsTable">

  <thead class="bg-gray-50 text-gray-600 sticky top-0">
    <tr>
      <th class="p-3 border-b whitespace-nowrap w-1/4">اسم الطالب</th>
      <th class="p-3 border-b whitespace-nowrap w-1/4">اسم الأب</th>
      <th class="p-3 border-b whitespace-nowrap w-1/4">الصف</th>
      <th class="p-3 border-b whitespace-nowrap w-1/4">سقف الشراء</th>
    </tr>
  </thead>

                <tbody class="divide-y divide-gray-200">
                  <!-- النتائج ستُملأ بواسطة JavaScript -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- المنتجات -->
      <div class="w-full lg:w-1/2 bg-white p-6 rounded-xl shadow-sm flex flex-col overflow-hidden">

        <h2 class="text-2xl font-bold text-primary-700 mb-6 pb-3 border-b border-gray-100">المنتجات</h2>

        <!-- التصنيفات -->
        <div class="mb-6">
          <h3 class="font-semibold text-gray-700 mb-3">التصنيفات</h3>
          <div id="categoriesContainer" class="flex flex-wrap gap-2">
            <!-- التصنيفات ديناميكية تظهر هنا -->
            <span class="text-gray-400">يرجى اختيار طالب أولاً</span>
          </div>
        </div>

        <!-- المنتجات المتاحة -->
        <div class="flex-1 overflow-hidden flex flex-col">
          <h3 class="font-semibold text-gray-700 mb-3">المنتجات المتاحة</h3>
          <div class="border border-gray-200 rounded-lg overflow-hidden flex-1">
            <div class="overflow-y-auto h-full">
              <table class="w-full text-right">
                <thead class="bg-gray-50 text-gray-600 sticky top-0">
                  <tr>
                    <th class="p-3 border-b">المنتج</th>
                    <th class="p-3 border-b">السعر</th>
                    <th class="p-3 border-b">الكمية</th>
                    <th class="p-3 border-b">الإجراء</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="productTable">
                  <!-- المنتجات ديناميكية تظهر هنا -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

 <!-- فاتورة البيع مع إمكانية التمرير -->
    <div class="flex-1 overflow-y-auto bg-gray-50 p-4 border-t border-gray-200">
      <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-sm">
        <!-- عنوان الفاتورة -->
        <h1 class="text-2xl font-bold text-primary-700 mb-6 pb-3 border-b border-gray-100">فاتورة البيع</h1>

        <!-- جدول الفاتورة مع ارتفاع ثابت وتمرير -->
        <div class="mb-6 overflow-hidden border border-gray-200 rounded-lg max-h-[300px] overflow-y-auto">
          <table class="w-full text-right" id="invoiceTable">
            <thead class="bg-gray-50 text-gray-600">
              <tr>
                <th class="p-3 border-b">المنتج</th>
                <th class="p-3 border-b">الكمية</th>
                <th class="p-3 border-b">سعر القطعة</th>
                <th class="p-3 border-b">الإجمالي</th>
                <th class="p-3 border-b">الإجراء</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <!-- الصفوف تتم إضافتها هنا ديناميكياً -->
            </tbody>
          </table>
        </div>

        <!-- الإجمالي -->
        <div class="bg-primary-50 text-primary-900 p-4 rounded-lg mb-6 border border-primary-100">
          <div class="flex justify-between items-center">
            <div>
              <span class="font-bold text-lg">إجمالي الفاتورة</span>
              <div id="limitInfo" class="text-sm mt-1 hidden">
                <span id="remainingLimitText"></span>
              </div>
            </div>
            <span id="totalAmount" class="font-bold text-2xl">0.00د.ل</span>
          </div>
        </div>

        <!-- زر التأكيد -->
        <div class="text-center">
          <button class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg text-lg font-bold shadow-md hover:shadow-lg transition-all" onclick="confirmSale()">
            تأكيد عملية البيع
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- مودال الرسائل -->
<div id="messageModal" class="fixed inset-0 bg-black bg-opacity-30 z-50 hidden items-center justify-center">
  <div class="bg-white p-6 rounded-xl shadow-xl max-w-md w-full text-center">
    <h2 id="modalTitle" class="text-xl font-bold mb-4 text-primary-700">رسالة</h2>
    <p id="modalMessage" class="text-gray-700 mb-6">...</p>
    <button onclick="closeModal()" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg">حسناً</button>
  </div>
</div>
<!-- مودال إدخال الرقم السري -->
<div id="pinModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white p-6 rounded-xl shadow-xl max-w-sm w-full text-center">
    <h2 class="text-xl font-bold mb-4 text-primary-700">أدخل الرقم السري للطالب</h2>
    <input type="password" id="pinInput" class="w-full p-3 border border-gray-300 rounded mb-4" placeholder="الرقم السري" />
    <p id="pinError" class="text-red-600 mb-4 hidden">الرقم السري غير صحيح، حاول مرة أخرى.</p>
    <div class="flex justify-center gap-4">
      <button id="cancelPinBtn" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">إلغاء</button>
      <button id="confirmPinBtn" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700">تأكيد</button>
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
  const limitInfoDiv = document.getElementById('limitInfo');
  const remainingLimitText = document.getElementById('remainingLimitText');
// مودال الرقم السري وعناصره
const pinModal = document.getElementById('pinModal');
const pinInput = document.getElementById('pinInput');
const pinError = document.getElementById('pinError');
const cancelPinBtn = document.getElementById('cancelPinBtn');
const confirmPinBtn = document.getElementById('confirmPinBtn');

// تخزين الرقم السري الصحيح مؤقتاً عند تحميل بيانات الطالب

  let invoiceItems = [];
  let allProducts = [];
  let currentStudentId = null;
  let currentCategory = null;
  let dailyLimit = 0;
let currentStudentPin = null;
function loadCategoriesAndProducts(studentId) {
    fetch(`/students/${studentId}/allowed-categories`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          if (data.student && data.student.daily_limit) {
            data.student.daily_limit = parseFloat(data.student.daily_limit) || 0;
          }

          updateStudentInfo(data.student);
          renderCategories(data.categories);
          allProducts = data.products;
          currentCategory = null;
          renderProducts(allProducts);

          if (data.student.daily_limit !== undefined) {
            dailyLimit = data.student.daily_limit;
            updateLimitInfo();
          }

          // تخزين الرقم السري من بيانات الطالب
          currentStudentPin = data.student.pin_code || null;

        } else {
          showError(data.message);
        }
      })
      .catch(err => {
        showError('حدث خطأ في جلب البيانات: ' + err.message);
      });
  }

  // استقبال البيانات من قارئ QR Code الخارجي
  studentSearchInput.addEventListener('keydown', function(e) {
    // إذا كان الإدخال يحتوي على رمز QR (عادةً ما ينتهي بمفتاح Enter)
    if (e.key === 'Enter') {
      const qrCode = this.value.trim();
      if (qrCode) {
        searchStudentByQR(qrCode);
          this.value = '';
      }
    }
  });

  // بحث الطلاب العادي
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
       // الكود المعدل
data.forEach(student => {
    const tr = document.createElement('tr');

    // بناء محتوى الصف
    tr.innerHTML = `
        <td class="p-2 border whitespace-nowrap text-sm font-medium text-gray-800">${student.full_name}</td>
        <td class="p-2 border whitespace-nowrap text-sm text-gray-700">${student.father_name ?? '—'}</td>
        <td class="p-2 border whitespace-nowrap text-sm text-gray-700">${student.class}</td>
        <td class="p-2 border whitespace-nowrap text-sm text-gray-700">${student.daily_limit ? parseFloat(student.daily_limit).toFixed(2) + ' د.ل' : '—'}</td>
    `;

    // التحقق إذا كان الطالب مؤرشفاً
    if (student.deleted_at) {
        // --- حالة الطالب المؤرشف ---
        // تغيير شكل الصف ليدل على أنه غير فعال
        tr.classList.add('opacity-50', 'bg-red-50', 'cursor-not-allowed');

        // إضافة علامة (مؤرشف) بجانب اسم الطالب
        tr.querySelector('td:first-child').innerHTML += ` <span class="text-red-600 font-bold">(مؤرشف)</span>`;

        // عند النقر، نعرض رسالة خطأ بدلاً من تحميل البيانات
        tr.addEventListener('click', () => {
            showModal('هذا الطالب مؤرشف ولا يمكن إجراء عمليات عليه.', 'عملية غير ممكنة');
        });

    } else {
        // --- حالة الطالب النشط (المنطق الأصلي) ---
        tr.classList.add('cursor-pointer', 'hover:bg-primary-50');
        tr.addEventListener('click', () => {
            currentStudentId = student.student_id;
            loadCategoriesAndProducts(currentStudentId);
            highlightSelectedStudent(tr);
            invoiceItems = [];
            renderInvoice();
        });
    }

    studentsTableBody.appendChild(tr);
});
      }).catch(() => {
        studentsTableBody.innerHTML = `<tr><td colspan="4" class="text-center p-2 text-red-500">خطأ في جلب الطلاب</td></tr>`;
      });
  });

  // البحث عن طريق QR Code
  function searchStudentByQR(qrCode) {
    fetch(`/students/search?query=${encodeURIComponent(qrCode)}`)
      .then(res => res.json())
      .then(data => {
        studentsTableBody.innerHTML = '';
        if (data.length === 0) {
          studentsTableBody.innerHTML = `<tr><td colspan="4" class="text-center text-red-500 p-2">لم يتم العثور على الطالب</td></tr>`;
          return;
        }

        // افترض أن QR Code يعيد طالباً واحداً فقط
        const student = data[0];
        const tr = document.createElement('tr');
        tr.classList.add('cursor-pointer', 'hover:bg-primary-50');
        tr.innerHTML = `
          <td class="p-2 border whitespace-nowrap text-sm font-medium text-gray-800">${student.full_name}</td>
          <td class="p-2 border whitespace-nowrap text-sm text-gray-700">${student.father_name ?? '—'}</td>
          <td class="p-2 border whitespace-nowrap text-sm text-gray-700">${student.class}</td>
          <td class="p-2 border whitespace-nowrap text-sm text-gray-700">${student.daily_limit ? parseFloat(student.daily_limit).toFixed(2) + ' د.ل' : '—'}</td>
        `;
        tr.addEventListener('click', () => {
          currentStudentId = student.student_id;
          loadCategoriesAndProducts(currentStudentId);
          highlightSelectedStudent(tr);
          invoiceItems = [];
          renderInvoice();
        });
        studentsTableBody.appendChild(tr);

        // تحديد الطالب تلقائياً
        currentStudentId = student.student_id;
        loadCategoriesAndProducts(currentStudentId);
        highlightSelectedStudent(tr);
        invoiceItems = [];
        renderInvoice();
      }).catch(() => {
        studentsTableBody.innerHTML = `<tr><td colspan="4" class="text-center p-2 text-red-500">حدث خطأ في جلب البيانات</td></tr>`;
      });
  }

  function highlightSelectedStudent(selectedRow) {
    studentsTableBody.querySelectorAll('tr').forEach(r => {
      r.classList.remove('bg-primary-100', 'text-white');
    });
    selectedRow.classList.add('bg-primary-100', 'text-white');
  }

  function loadCategoriesAndProducts(studentId) {
    fetch(`/students/${studentId}/allowed-categories`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          if (data.student && data.student.daily_limit) {
            data.student.daily_limit = parseFloat(data.student.daily_limit) || 0;
          }

          updateStudentInfo(data.student);
          renderCategories(data.categories);
          allProducts = data.products;
          currentCategory = null;
          renderProducts(allProducts);

          if (data.student.daily_limit !== undefined) {
            dailyLimit = data.student.daily_limit;
            updateLimitInfo();
          }
             currentStudentPin = data.student.pin_code || null;
        } else {
          showError(data.message);
        }
      })
      .catch(err => {
        showError('حدث خطأ في جلب البيانات: ' + err.message);
      });
  }

  function updateStudentInfo(student) {
    const studentInfoContainer = document.getElementById('studentInfoContainer');
    const dailyLimitDisplay = student.daily_limit !== undefined && student.daily_limit !== null ?
      parseFloat(student.daily_limit).toFixed(2) + ' د.ل' :
      'غير محدد';

    studentInfoContainer.innerHTML = `
      <div class="bg-blue-50 p-3 rounded mb-4">
        <h3 class="font-bold">الطالب المحدد:</h3>
        <p>الاسم: ${student.full_name}</p>
        <p>الصف: ${student.class}</p>
        <p class="text-gray-700">
          سقف الشراء اليومي: ${dailyLimitDisplay}
        </p>
      </div>
    `;
  }

  function updateLimitInfo() {
    const total = invoiceItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (dailyLimit > 0) {
      limitInfoDiv.classList.remove('hidden');
      const formattedLimit = parseFloat(dailyLimit).toFixed(2);

      if (total > dailyLimit) {
        remainingLimitText.innerHTML = `
          <span class="text-red-600 font-bold">
            تحذير: تجاوز سقف الشراء اليومي (${formattedLimit} د.ل)
          </span>
        `;
      } else {
        remainingLimitText.innerHTML = `
          <span class="text-gray-700">
            السقف اليومي: ${formattedLimit} د.ل
          </span>
        `;
      }
    } else {
      limitInfoDiv.classList.add('hidden');
    }
  }

  function renderCategories(categories) {
    if (!categories || categories.length === 0) {
      categoriesContainer.innerHTML = `<span class="text-gray-400">لا توجد تصنيفات متاحة لهذا الطالب</span>`;
      productTableBody.innerHTML = '';
      return;
    }

    categoriesContainer.innerHTML = '';

    const allLabel = document.createElement('label');
    allLabel.className = "cursor-pointer select-none";
    allLabel.innerHTML = `
      <input type="radio" name="category" value="" class="hidden" onchange="onCategoryChange('')" ${!currentCategory ? 'checked' : ''}>
      <span class="px-3 py-1 border rounded hover:bg-primary-50 bg-primary-100">عرض الكل</span>
    `;
    categoriesContainer.appendChild(allLabel);

    categories.forEach(cat => {
      const label = document.createElement('label');
      label.className = "cursor-pointer select-none";
      label.innerHTML = `
        <input type="radio" name="category" value="${cat.name}" class="hidden" onchange="onCategoryChange('${cat.name}')" ${currentCategory === cat.name ? 'checked' : ''}>
        <span class="px-3 py-1 border rounded hover:bg-primary-50 ${currentCategory === cat.name ? 'bg-primary-100' : ''}">${cat.name}</span>
      `;
      categoriesContainer.appendChild(label);
    });
  }

  function onCategoryChange(category) {
    currentCategory = category === "" ? null : category;
    renderProducts(allProducts);
  }

  function escapeSingleQuote(str) {
    return str.replace(/'/g, "\\'");
  }

  function renderProducts(products) {
    products.forEach(p => {
      if (typeof p.price === 'string') {
        p.price = parseFloat(p.price);
      }
    });

    let filtered = currentCategory ?
      products.filter(p => p.category_name.trim() === currentCategory.trim()) :
      products;

    if (!filtered.length) {
      productTableBody.innerHTML = `
        <tr>
          <td colspan="4" class="p-2 border text-center text-gray-500">
            لا توجد منتجات ${currentCategory ? 'في هذا التصنيف' : 'متاحة'}
          </td>
        </tr>
      `;
      return;
    }

    productTableBody.innerHTML = '';
    filtered.forEach(product => {
      const isDisabled = product.quantity <= 0;
      const tr = document.createElement('tr');
      tr.className = isDisabled ? 'opacity-50' : '';
      tr.innerHTML = `
        <td class="p-2 border ${isDisabled ? 'text-gray-400' : ''}">${product.name}</td>
        <td class="p-2 border ${isDisabled ? 'text-gray-400' : ''}">${product.price.toFixed(2)}د.ل</td>
        <td class="p-2 border text-center ${isDisabled ? 'text-red-500' : ''}">
          ${isDisabled ? 'غير متوفر' : product.quantity}
        </td>
        <td class="p-2 border text-center">
          <button onclick="addToInvoice(${product.id}, '${escapeSingleQuote(product.name)}', ${product.price}, ${product.quantity})"
            class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700"
            ${isDisabled ? 'disabled class="opacity-50 cursor-not-allowed"' : ''}>
            +
          </button>
        </td>
      `;
      productTableBody.appendChild(tr);
    });
  }

  function addToInvoice(id, name, price, availableQty) {
    const existingItem = invoiceItems.find(i => i.id === id);

    if (existingItem) {
      if (existingItem.quantity < availableQty) {
        existingItem.quantity++;
      } else {
        showModal('لا يمكن طلب كمية أكبر من الكمية المتاحة (' + availableQty + ')');
        return;
      }
    } else {
      if (availableQty > 0) {
        invoiceItems.push({
          id: id,
          name: name,
          price: price,
          quantity: 1
        });
      } else {
        showModal('هذا المنتج غير متوفر حالياً');
        return;
      }
    }

    renderInvoice();
  }

  function removeItem(index) {
    invoiceItems.splice(index, 1);
    renderInvoice();
  }

  function updateQuantity(index, delta) {
    const item = invoiceItems[index];
    const product = allProducts.find(p => p.id === item.id);

    if (!product) return;

    let newQty = item.quantity + delta;
    if (newQty > product.quantity) {
      showModal('لا يمكن زيادة الكمية عن المتوفر.');
      return;
    } else if (newQty <= 0) {
      removeItem(index);
    } else {
      item.quantity = newQty;
      renderInvoice();
    }
  }

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
        <td class="p-2 border text-center">${item.price.toFixed(2)} د.ل</td>
        <td class="p-2 border text-center">${itemTotal.toFixed(2)}د.ل</td>
        <td class="p-2 border text-center">
          <button onclick="removeItem(${i})" class="text-red-600 font-bold">إزالة</button>
        </td>
      `;
      invoiceTableBody.appendChild(row);
    });
    totalAmountSpan.textContent = `${total.toFixed(2)} د.ل`;
    updateLimitInfo();
  }

  async function confirmSale() {
    if (!currentStudentId) {
       showModal('يرجى اختيار طالب أولاً.');
      return;
    }
    if (invoiceItems.length === 0) {
       showModal('لا يوجد منتجات في الفاتورة.');
      return;
    }
   pinError.classList.add('hidden');
    pinModal.classList.remove('hidden');
    pinModal.classList.add('flex');
    pinInput.focus();
  }
   cancelPinBtn.addEventListener('click', () => {
    pinModal.classList.add('hidden');
    pinModal.classList.remove('flex');
  });
 confirmPinBtn.addEventListener('click', () => {
    const enteredPin = pinInput.value.trim();
    if (!enteredPin) {
      pinError.textContent = 'الرجاء إدخال الرقم السري.';
      pinError.classList.remove('hidden');
      return;
    }
    if (enteredPin === currentStudentPin) {
      pinModal.classList.add('hidden');
      pinModal.classList.remove('flex');
      pinError.classList.add('hidden');
      sendSaleToServer();
    } else {
      pinError.textContent = 'الرقم السري غير صحيح، حاول مرة أخرى.';
      pinError.classList.remove('hidden');
    }
  });
  async function sendSaleToServer() {
    const totalAmount = invoiceItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (dailyLimit > 0 && totalAmount > dailyLimit) {
       showModal(`لا يمكن إتمام الشراء، السقف اليومي هو ${dailyLimit.toFixed(2)} د.ل`);
      return;
    }

    try {
      const saleData = {
        student_id: currentStudentId,
           pin_code: pinInput.value.trim(),
        items: invoiceItems.map(i => ({
          product_id: i.id,
          quantity: i.quantity,
          price: i.price
        })),
        total_amount: totalAmount
      };

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                       getCookie('XSRF-TOKEN');

      const response = await fetch('/orders', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify(saleData),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'حدث خطأ في تأكيد البيع');
      }

      showModal('تم تأكيد عملية البيع بنجاح');
      invoiceItems = [];
      renderInvoice();
      loadCategoriesAndProducts(currentStudentId);
    } catch (err) {
      showModal(err.message || 'حدث خطأ أثناء تأكيد البيع');
      console.error(err);
    }
  }
  // زر تأكيد البيع يربط مع confirmSale
  document.getElementById('confirmSaleBtn').addEventListener('click', confirmSale);

  function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
  }

  function showError(message) {
    alert(message);
  }
    function showModal(message, title = "رسالة") {
    document.getElementById("modalTitle").textContent = title;
    document.getElementById("modalMessage").textContent = message;
    document.getElementById("messageModal").classList.remove("hidden");
    document.getElementById("messageModal").classList.add("flex");
  }

  function closeModal() {
    document.getElementById("messageModal").classList.add("hidden");
    document.getElementById("messageModal").classList.remove("flex");
  }

</script>

</body>
</html>
