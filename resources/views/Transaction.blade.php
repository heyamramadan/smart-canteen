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
        <span class="ml-2">💸</span> سجل المعاملات
      </h2>

      <div class="flex items-center space-x-4 space-x-reverse">
  <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-4 flex-wrap md:flex-nowrap space-x-reverse">
  <!-- 🔍 البحث -->
  <div class="relative">
    <input
      type="text"
        id="search"
      name="search"
      value="{{ request('search') }}"
      placeholder="ابحث باسم ولي الأمر أو الطالب"
      class="pr-10 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
    />
    <button type="submit" class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600">
      🔍
    </button>
  </div>

  <!-- ✅ فلتر النوع -->
  <select name="type" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
    <option value="">كل الأنواع</option>
    <option value="إيداع" {{ request('type') === 'إيداع' ? 'selected' : '' }}>إيداع</option>
    <option value="سحب" {{ request('type') === 'سحب' ? 'selected' : '' }}>سحب</option>
  </select>

  <!-- ✅ التاريخ المخصص -->
<div class="flex gap-2 items-center">
  <input type="number" name="day" placeholder="اليوم" min="1" max="31"
         value="{{ request('day') }}"
         class="w-20 border rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
  <input type="number" name="month" placeholder="الشهر" min="1" max="12"
         value="{{ request('month') }}"
         class="w-20 border rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
  <input type="number" name="year" placeholder="السنة" min="2000" max="2100"
         value="{{ request('year') }}"
         class="w-24 border rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
</div>

  <!-- ✅ زر التصفية -->
  <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">
    تصفية
  </button>
</form>



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
    <tbody id="results" class="divide-y divide-gray-200">

        @forelse ($transactions as $transaction)
        @php
    $parent = $transaction->wallet->parent ?? null;
    $parentUser = $parent?->user;
    $student = $parent?->students?->first();
    $balanceBefore = $transaction->type === 'إيداع'
        ? ($transaction->amount ? $transaction->wallet->balance - $transaction->amount : $transaction->wallet->balance)
        : ($transaction->amount ? $transaction->wallet->balance + $transaction->amount : $transaction->wallet->balance);
@endphp

          <tr class="hover:bg-gray-50 transition">
            <td class="p-3 text-sm">{{ $loop->iteration }}</td>
            <td class="p-3 text-sm">{{ $parentUser?->full_name ?? $parentUser?->name ?? 'غير معروف' }}</td>
            <td class="p-3 text-sm">{{ $student?->full_name ?? 'غير مرتبط' }}</td>
            <td class="p-3">
              <span class="px-3 py-1 rounded-full text-xs
                {{ $transaction->type === 'إيداع' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $transaction->type }}
              </span>
            </td>
            <td class="p-3 font-medium {{ $transaction->type === 'إيداع' ? 'text-green-600' : 'text-red-600' }}">
              {{ $transaction->type === 'إيداع' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} ر.س
            </td>
            <td class="p-3">{{ number_format($balanceBefore, 2) }} د.ل</td>
            <td class="p-3">{{ number_format($transaction->wallet->balance, 2) }} د.ل</td>
            <td class="p-3">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y h:i A') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center p-6 text-gray-400">لا توجد معاملات</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- التذييل مع الصفحات -->
<div class="flex justify-between items-center text-sm text-gray-600">
  <div>
    عرض <span class="font-bold">{{ $transactions->firstItem() }}</span>
    إلى <span class="font-bold">{{ $transactions->lastItem() }}</span>
    من <span class="font-bold">{{ $transactions->total() }}</span> معاملات
  </div>
  <div class="flex space-x-2 space-x-reverse">
    {{ $transactions->links('pagination::tailwind') }}
  </div>
</div>
<script>
  const searchInput = document.getElementById('search');
const results = document.getElementById('results');
const form = searchInput.closest('form');

searchInput.addEventListener('input', function () {
  const query = this.value.trim();

  if (query === '') {
    // إعادة تحميل الصفحة مع بقية الفلاتر إذا كانت موجودة في الرابط
    // بدلاً من حذف الفلاتر، نعيد توجيه المستخدم مع باقي استعلامات الفلترة
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.delete('search'); // حذف نص البحث فقط
    window.location.href = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
    return;
  }

  // اجمع باقي الفلاتر (type, date) لتضمينها في طلب البحث المباشر
  const urlParams = new URLSearchParams();
  urlParams.append('search', query);

  // أضف فلتر النوع والتاريخ من الـ form إذا موجودة
  const type = form.querySelector('select[name="type"]').value;
  if (type) urlParams.append('type', type);

  const day = form.querySelector('input[name="day"]').value;
const month = form.querySelector('input[name="month"]').value;
const year = form.querySelector('input[name="year"]').value;

if (day) urlParams.append('day', day);
if (month) urlParams.append('month', month);
if (year) urlParams.append('year', year);


  fetch(`/transactions/search?${urlParams.toString()}`)
    .then(response => response.json())
    .then(data => {
      if (data.length === 0) {
        results.innerHTML = `
          <tr>
            <td colspan="8" class="text-center p-6 text-gray-400">لا توجد نتائج</td>
          </tr>`;
        return;
      }

      let html = '';
      data.forEach(transaction => {
        const typeClass = transaction.type === 'إيداع'
          ? 'bg-green-100 text-green-800'
          : 'bg-red-100 text-red-800';
        const amountColor = transaction.type === 'إيداع'
          ? 'text-green-600'
          : 'text-red-600';
        const sign = transaction.type === 'إيداع' ? '+' : '-';

        html += `
          <tr class="hover:bg-gray-50 transition">
            <td class="p-3 text-sm">#TRX-${transaction.id}</td>
            <td class="p-3 text-sm">${transaction.parent_name}</td>
            <td class="p-3 text-sm">${transaction.student_names || 'غير مرتبط'}</td>
            <td class="p-3"><span class="px-3 py-1 rounded-full text-xs ${typeClass}">${transaction.type}</span></td>
            <td class="p-3 font-medium ${amountColor}">${sign}${parseFloat(transaction.amount).toFixed(2)} ر.س</td>
            <td class="p-3">-</td>
            <td class="p-3">-</td>
            <td class="p-3">${transaction.created_at}</td>
          </tr>`;
      });

      results.innerHTML = html;
    })
    .catch(() => {
      results.innerHTML = `
        <tr>
          <td colspan="8" class="text-center p-6 text-red-400">حدث خطأ أثناء جلب النتائج</td>
        </tr>`;
    });
});

</script>


</body>
</html>
