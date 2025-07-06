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
  <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-4 flex-wrap md:flex-nowrap space-x-reverse">
  <!-- 🔍 البحث -->
  <div class="relative">
    <input
      type="text"
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

  <!-- ✅ فلتر التاريخ -->
  <select name="date" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
    <option value="">كل التواريخ</option>
    <option value="اليوم" {{ request('date') === 'اليوم' ? 'selected' : '' }}>اليوم</option>
    <option value="أسبوع" {{ request('date') === 'أسبوع' ? 'selected' : '' }}>أسبوع</option>
    <option value="شهر" {{ request('date') === 'شهر' ? 'selected' : '' }}>شهر</option>
  </select>

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
      <tbody class="divide-y divide-gray-200">
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
            <td class="p-3 text-sm">#TRX-{{ $transaction->transaction_id }}</td>
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


</body>
</html>
