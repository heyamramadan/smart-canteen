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

  <div class="flex-1 p-6 overflow-auto">

    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center flex-wrap gap-4">
      <h2 class="text-lg font-bold text-primary-700 flex items-center">
        <span class="ml-2">💸</span> سجل المعاملات
      </h2>

      {{-- نموذج البحث والفلاتر مدمج --}}
      <form method="GET" action="{{ route('transactions.index') }}" class="flex items-center gap-4 flex-wrap md:flex-nowrap">
        <div class="relative">
          <input
            type="text"
            id="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="ابحث بالاسم..."
            class="w-full md:w-auto pr-4 pl-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
          />
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔍</span>
        </div>

        <select name="type" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
          <option value="">كل الأنواع</option>
          <option value="إيداع" {{ request('type') === 'إيداع' ? 'selected' : '' }}>إيداع</option>
          <option value="سحب" {{ request('type') === 'سحب' ? 'selected' : '' }}>سحب</option>
        </select>

        <div class="flex gap-2 items-center">
          <input type="number" name="day" placeholder="يوم" min="1" max="31" value="{{ request('day') }}" class="w-20 border rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
          <input type="number" name="month" placeholder="شهر" min="1" max="12" value="{{ request('month') }}" class="w-20 border rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
          <input type="number" name="year" placeholder="سنة" min="2020" max="2100" value="{{ request('year') }}" class="w-24 border rounded-lg px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>

        <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">
          تصفية
        </button>
      </form>
    </div>

   <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
      <div class="overflow-x-auto">
        <table class="w-full text-right">
          <thead class="bg-gray-50 border-b">
            <tr>
              <th class="p-3 text-sm text-gray-500 font-semibold">#</th>
              <th class="p-3 text-sm text-gray-500 font-semibold">ولي الأمر</th>
              <th class="p-3 text-sm text-gray-500 font-semibold">النوع</th>
              <th class="p-3 text-sm text-gray-500 font-semibold">المبلغ</th>
              <th class="p-3 text-sm text-gray-500 font-semibold">الرصيد بعد</th>
              <th class="p-3 text-sm text-gray-500 font-semibold">التاريخ</th>
            </tr>
          </thead>
          <tbody id="results" class="divide-y divide-gray-200">
            @forelse ($transactions as $transaction)
            @php
                // ✅ تعديل: الوصول إلى المستخدم (ولي الأمر) مباشرة من المحفظة
                $user = $transaction->wallet->user;
            @endphp
              <tr class="hover:bg-gray-50 transition">
                <td class="p-3 text-sm text-gray-600">{{ $transaction->transaction_id }}</td>
                <td class="p-3 text-sm font-medium text-gray-800">{{ $user->full_name ?? 'ولي أمر محذوف' }}</td>
                <td class="p-3">
                  <span class="px-3 py-1 rounded-full text-xs font-medium
                    {{ $transaction->type === 'إيداع' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $transaction->type }}
                  </span>
                </td>
                <td class="p-3 font-semibold {{ $transaction->type === 'إيداع' ? 'text-green-600' : 'text-red-600' }}">
                  {{-- استخدام القيمة المطلقة للمبلغ لعرضه دائماً كرقم موجب --}}
                  {{ number_format(abs($transaction->amount), 2) }} د.ل
                </td>
                <td class="p-3 text-sm font-bold text-gray-800">{{ number_format($transaction->wallet->balance, 2) }} د.ل</td>
                <td class="p-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y h:i A') }}
</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center p-6 text-gray-400">لا توجد معاملات تطابق البحث</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="px-4 py-2">
      {{ $transactions->appends(request()->query())->links('pagination::tailwind') }}
    </div>
  </div>
</div>

<script>
  const searchInput = document.getElementById('search');
  const resultsTableBody = document.getElementById('results');
  const form = document.querySelector('form'); // الحصول على أول فورم في الصفحة

  searchInput.addEventListener('input', function () {
      const query = this.value.trim();
      const urlParams = new URLSearchParams(window.location.search);

      if (query === '') {
          // إذا كان البحث فارغًا، قم بإعادة تحميل الصفحة مع الفلاتر الحالية
          urlParams.delete('search');
          window.location.search = urlParams.toString();
          return;
      }

      // جمع كل الفلاتر من الفورم
      urlParams.set('search', query);
      const formData = new FormData(form);
      for (const [key, value] of formData.entries()) {
          if (value) {
              urlParams.set(key, value);
          } else {
              urlParams.delete(key);
          }
      }

      fetch(`/transactions/search?${urlParams.toString()}`)
          .then(response => response.json())
          .then(data => {
              if (data.length === 0) {
                  resultsTableBody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-gray-400">لا توجد نتائج</td></tr>`;
                  return;
              }

              let html = '';
              data.forEach(tx => {
                  const typeClass = tx.type === 'إيداع' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                  const amountColor = tx.type === 'إيداع' ? 'text-green-600' : 'text-red-600';

                  html += `
                      <tr class="hover:bg-gray-50 transition">
                          <td class="p-3 text-sm text-gray-600">${tx.id}</td>
                          <td class="p-3 text-sm font-medium text-gray-800">${tx.parent_name}</td>
                          <td class="p-3"><span class="px-3 py-1 rounded-full text-xs font-medium ${typeClass}">${tx.type}</span></td>
                          <td class="p-3 font-semibold ${amountColor}">${tx.amount} د.ل</td>
                          <td class="p-3 text-sm font-bold text-gray-800">${tx.balance_after} د.ل</td>
                          <td class="p-3 text-sm text-gray-500">${tx.created_at}</td>
                      </tr>`;
              });
              resultsTableBody.innerHTML = html;
        //  })
         // .catch(() => {
              resultsTableBody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-red-400">حدث خطأ</td></tr>`;
         // });
  });
</script>

</body>
</html>
