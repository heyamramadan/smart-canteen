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
  <div class="w-64 bg-white border-l">
    @include('layouts.sidebar')
  </div>

  <!-- المحتوى الرئيسي -->
  <div class="flex-1 flex flex-col overflow-hidden">

    <!-- الصف العلوي: بيانات الطالب والمنتجات -->
    <div class="flex flex-col md:flex-row h-1/2">

      <!-- بيانات الطالب -->
      <div class="w-full md:w-1/2 bg-white p-4 border-l overflow-y-auto">
        <h2 class="text-xl font-bold text-primary mb-6 text-center">بيانات الطالب</h2>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">بحث عن طالب</label>
          <input type="text" class="w-full p-2 border rounded mb-4" placeholder="ادخل رقم أو اسم الطالب" />

          <div class="bg-primary-50 p-3 rounded-lg">
            <div class="flex items-center mb-3">
              <div class="w-10 h-10 rounded-full bg-primary-200 flex items-center justify-center ml-2">ص</div>
              <div>
                <div class="font-semibold">محمد أحمد</div>
                <div class="text-xs text-gray-500">رقم: 12345</div>
              </div>
            </div>

            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-sm">الصف:</span>
                <span class="text-sm font-medium">الثالث أ</span>
              </div>
              <div class="flex justify-between">
                <span class="text-sm">الرصيد:</span>
                <span class="text-sm font-medium text-green-600">150.00 ر.س</span>
              </div>
            </div>
          </div>
        </div>

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
      <div class="w-full md:w-1/2 bg-white p-4 overflow-y-auto border-r">
        <h2 class="text-xl font-bold text-primary mb-6 text-center">المنتجات</h2>

        <!-- التصنيفات -->
        <div class="mb-4">
          <h3 class="font-semibold text-primary mb-2">التصنيفات</h3>
          <div class="flex flex-wrap gap-4">
            <label class="cursor-pointer">
              <input type="radio" name="category" value="الوجبات" class="hidden" onchange="filterProducts(this.value)">
              <span class="px-3 py-1 border rounded hover:bg-primary-50">الوجبات</span>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="category" value="المشروبات" class="hidden" onchange="filterProducts(this.value)">
              <span class="px-3 py-1 border rounded hover:bg-primary-50">المشروبات</span>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="category" value="الحلويات" class="hidden" onchange="filterProducts(this.value)">
              <span class="px-3 py-1 border rounded hover:bg-primary-50">الحلويات</span>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="category" value="السندويشات" class="hidden" onchange="filterProducts(this.value)">
              <span class="px-3 py-1 border rounded hover:bg-primary-50">السندويشات</span>
            </label>
          </div>
        </div>

        <!-- المنتجات المتاحة -->
        <div>
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
              <tr data-category="الوجبات">
                <td class="p-2 border">ستيك لحم</td>
                <td class="p-2 border">33.00 ر.س</td>
        <td class="p-2 border text-center">5</td>
          <td class="p-2 border text-center">
   <button onclick="addToInvoice(...)" class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700">
  +
</button>

  </td>
              </tr>
              <tr data-category="الوجبات">
                <td class="p-2 border">دجاج مشوي</td>
                <td class="p-2 border">28.00 ر.س</td>
        <td class="p-2 border text-center">50</td>
                 <td class="p-2 border text-center">
    <button onclick="addToInvoice(...)" class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700">
  +
</button>

  </td>
              </tr>
              <tr data-category="الوجبات">
                <td class="p-2 border">شوربة اليوم</td>
                <td class="p-2 border">7.50 ر.س</td>
          <td class="p-2 border text-center">77</td>
            <td class="p-2 border text-center">
   <button onclick="addToInvoice(...)" class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700">
  +
</button>

  </td>
              </tr>
              <tr data-category="المشروبات">
                <td class="p-2 border">عصير برتقال</td>
                <td class="p-2 border">5.00 ر.س</td>
          <td class="p-2 border text-center">770</td>
            <td class="p-2 border text-center">
 <button onclick="addToInvoice(...)" class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700">
  +
</button>

  </td>
              </tr>
              <tr data-category="الحلويات">
                <td class="p-2 border">كعكة شوكولاتة</td>
                <td class="p-2 border">10.00 ر.س</td>
          <td class="p-2 border text-center">77</td>
            <td class="p-2 border text-center">
<button onclick="addToInvoice(...)" class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700">
  +
</button>

  </td>
              </tr>
              <tr data-category="السندويشات">
                <td class="p-2 border">سندويش جبن</td>
                <td class="p-2 border">6.00 ر.س</td>
        <td class="p-2 border text-center">400</td>
          <td class="p-2 border text-center">
 <button onclick="addToInvoice(...)" class="bg-primary-500 text-white px-2 py-1 rounded hover:bg-primary-700">
  +
</button>

  </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- الفاتورة -->
    <div class="flex-1 p-4 bg-white border-t overflow-y-auto">
      <div class="max-w-4xl mx-auto">

        <!-- عنوان الفاتورة -->
        <div class="mb-4">
          <h1 class="text-xl font-bold text-primary">فاتورة بيع</h1>
          <div class="flex justify-between">
            <p class="text-gray-600">التاريخ: <span id="currentDate"></span></p>
            <p class="text-gray-600">الطالب: محمد أحمد - الصف: الثالث أ</p>
          </div>
        </div>

        <!-- جدول الطلبات -->
        <div class="mb-4">
          <table class="w-full border-collapse">
            <thead>
              <tr class="bg-primary-100">
                <th class="p-2 border text-right">اسم الصنف</th>
                <th class="p-2 border">الكمية</th>
                <th class="p-2 border">السعر</th>
                <th class="p-2 border">الإجمالي</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="p-2 border">ستيك لحم</td>
                <td class="p-2 border text-center">
                  <button class="bg-primary text-white px-2 rounded">-</button>
                  <span class="mx-2">2</span>
                  <button class="bg-primary text-white px-2 rounded">+</button>
                </td>
                <td class="p-2 border text-center">33.00 ر.س</td>
                <td class="p-2 border text-center">66.00 ر.س</td>
              </tr>
              <tr>
                <td class="p-2 border">شوربة اليوم</td>
                <td class="p-2 border text-center">
                  <button class="bg-primary text-white px-2 rounded">-</button>
                  <span class="mx-2">1</span>
                  <button class="bg-primary text-white px-2 rounded">+</button>
                </td>
                <td class="p-2 border text-center">7.50 ر.س</td>
                <td class="p-2 border text-center">7.50 ر.س</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- ملخص الفاتورة -->
        <div class="bg-primary-50 text-black p-4 rounded-lg">
          <div class="flex justify-between mb-2">
            <span class="font-semibold">المجموع الفرعي</span>
            <span>73.50 ر.س</span>
          </div>
          <div class="flex justify-between mb-2">
            <span class="font-semibold">الخصم</span>
            <span>-0.00 ر.س</span>
          </div>
          <div class="flex justify-between mb-2">
            <span class="font-semibold">الضريبة (12%)</span>
            <span>8.82 ر.س</span>
          </div>
          <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
            <span>الإجمالي</span>
            <span>82.32 ر.س</span>
          </div>
        </div>

        <!-- زر التأكيد -->
        <div class="mt-4 text-center">
          <button class="bg-primary hover:bg-secondary text-white px-6 py-2 rounded-lg text-lg font-bold">
            تأكيد عملية البيع
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript -->
<script>
  // تصفية المنتجات حسب التصنيف
  function filterProducts(category) {
    const rows = document.querySelectorAll("#productTable tr");
    rows.forEach(row => {
      row.style.display = row.dataset.category === category ? "" : "none";
    });
  }

  // عرض التاريخ الحالي
  document.getElementById("currentDate").textContent = new Date().toLocaleDateString('ar-SA');

  function addToInvoice(name, price) {
    alert(`تمت إضافة ${name} بسعر ${price} ر.س إلى الفاتورة`);
    // هنا يمكنك لاحقًا تعديل الدالة لإضافة صف جديد في جدول الفاتورة فعليًا
  }
</script>

</body>
</html>
