<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>سجل المعاملات - المقصف الذكي</title>
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
  </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen">
  @include('layouts.sidebar')

  <!-- المحتوى الرئيسي -->
  <div class="flex-1 p-6 overflow-auto">

    <!-- شريط العنوان والبحث -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
      <h2 class="text-lg font-bold text-primary-700 flex items-center">
        <span class="ml-2">💳</span> سجل المعاملات
      </h2>

      <div class="flex items-center space-x-4 space-x-reverse">
        <!-- بحث -->
        <form method="GET" action="{{ route('transactions.index') }}" class="relative">
          <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="ابحث..."
            class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
          />
          <button type="submit" class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
            🔍
          </button>
        </form>

        <!-- فلاتر -->
        <select name="type" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
          <option>كل الأنواع</option>
          <option>إيداع</option>
          <option>سحب</option>
        </select>

        <select name="date" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
          <option>كل التواريخ</option>
          <option>اليوم</option>
          <option>أسبوع</option>
          <option>شهر</option>
        </select>
      </div>
    </div>

    <!-- جدول المعاملات -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
      <div class="overflow-x-auto">
        <table class="w-full text-right">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-3 text-sm text-gray-500">رقم</th>
              <th class="p-3 text-sm text-gray-500">ولي الأمر</th>
              <th class="p-3 text-sm text-gray-500">الطالب</th>
              <th class="p-3 text-sm text-gray-500">النوع</th>
              <th class="p-3 text-sm text-gray-500">المبلغ</th>
              <th class="p-3 text-sm text-gray-500">قبل</th>
              <th class="p-3 text-sm text-gray-500">بعد</th>
              <th class="p-3 text-sm text-gray-500">التاريخ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <!-- صف إيداع -->
            <tr class="hover:bg-gray-50 transition">
              <td class="p-3 text-sm">#TRX-1001</td>
              <td class="p-3 text-sm">أحمد محمد</td>
              <td class="p-3 text-sm">محمد أحمد</td>
              <td class="p-3">
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs">إيداع</span>
              </td>
              <td class="p-3 text-green-600 font-medium">+50.00 ر.س</td>
              <td class="p-3">120.00 ر.س</td>
              <td class="p-3">170.00 ر.س</td>
              <td class="p-3">05/07/2025 10:30 ص</td>

            </tr>

            <!-- صف سحب -->
            <tr class="hover:bg-gray-50 transition">
              <td class="p-3 text-sm">#TRX-1002</td>
              <td class="p-3 text-sm">علي حسن</td>
              <td class="p-3 text-sm">حسن علي</td>
              <td class="p-3">
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs">سحب</span>
              </td>
              <td class="p-3 text-red-600 font-medium">-15.00 ر.س</td>
              <td class="p-3">85.00 ر.س</td>
              <td class="p-3">70.00 ر.س</td>
              <td class="p-3">05/07/2025 11:45 ص</td>
            </tr>

            <!-- صف إضافي مثال -->
            <!-- ... -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- التذييل -->
    <div class="flex justify-between items-center text-sm text-gray-600">
      <div>
        عرض <span class="font-bold">1</span> إلى <span class="font-bold">4</span> من <span class="font-bold">4</span> معاملات
      </div>
      <div class="flex space-x-2 space-x-reverse">
        <button class="px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-500" disabled>السابق</button>
        <button class="px-4 py-2 rounded-lg bg-primary-500 text-white">1</button>
        <button class="px-4 py-2 rounded-lg border border-gray-300 bg-gray-50 text-gray-500" disabled>التالي</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
