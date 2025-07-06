<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>التقرير اليومي للمبيعات</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
    body { font-family: 'Tajawal', sans-serif; }
    .product-image { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
    button { transition: background-color 0.3s ease; }
  </style>
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

<div class="flex min-h-screen">
  @include('layouts.sidebar')

  <div class="flex-1 overflow-y-auto p-6">
    <!-- رأس الصفحة -->
    <div class="bg-white rounded-xl shadow-lg p-4 mb-6 flex justify-between items-center">
      <h2 class="text-lg font-bold text-primary-700 flex items-center">
        <i class="fas fa-calendar-day ml-2"></i> التقرير اليومي للمبيعات
      </h2>
      <div class="text-gray-600 font-medium" id="current-date">التاريخ: {{ $selectedDate }}</div>
    </div>

    <!-- فلترة حسب التاريخ -->
    <div class="bg-white rounded-xl shadow-lg p-4 mb-6 flex justify-between items-center">
      <form method="GET" class="flex items-center gap-4">
        <label for="date" class="text-sm font-medium text-gray-600">اختر اليوم:</label>
        <input type="date" id="date" name="date"
               value="{{ $selectedDate }}"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" />
        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm">
          تطبيق
        </button>
      </form>
    </div>

    <!-- بطاقات الملخص -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-4 rtl:space-x-reverse">
        <div class="text-blue-600 text-3xl"><i class="fas fa-money-bill-wave"></i></div>
        <div>
          <h3 class="text-gray-500">إجمالي المبيعات</h3>
          <p class="text-xl font-bold">{{ number_format($totalSales, 2) }} د.ل</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-4 rtl:space-x-reverse">
        <div class="text-green-600 text-3xl"><i class="fas fa-shopping-cart"></i></div>
        <div>
          <h3 class="text-gray-500">عدد الطلبات</h3>
          <p class="text-xl font-bold">{{ $totalOrders }}</p>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-4 rtl:space-x-reverse">
        <div class="text-purple-600 text-3xl"><i class="fas fa-box-open"></i></div>
        <div>
          <h3 class="text-gray-500">المنتجات المباعة</h3>
          <p class="text-xl font-bold">{{ $totalItemsSold }}</p>
        </div>
      </div>
    </div>

    <!-- جدول تفاصيل المبيعات -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
      <div class="p-4 border-b border-gray-200 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-primary-700">تفاصيل المبيعات</h3>
        <button onclick="exportTableToCSV()" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg text-sm flex items-center">
          <i class="fas fa-download ml-2"></i> تصدير CSV
        </button>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-right">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="p-3 text-sm text-gray-500">رقم الفاتورة</th>
              <th class="p-3 text-sm text-gray-500">الطالب</th>
              <th class="p-3 text-sm text-gray-500">المنتج</th>
              <th class="p-3 text-sm text-gray-500">الكمية</th>
              <th class="p-3 text-sm text-gray-500">السعر</th>
              <th class="p-3 text-sm text-gray-500">المجموع</th>
              <th class="p-3 text-sm text-gray-500">التاريخ</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse($orderItems as $item)
              <tr class="hover:bg-gray-50 transition">
                <td class="p-3 text-sm text-gray-700 font-medium">#ORD-{{ $item->order_id }}</td>
                <td class="p-3 text-sm text-gray-700">{{ $item->order->student->full_name ?? '-' }}</td>
                <td class="p-3 text-sm text-gray-700">{{ $item->product->name ?? '-' }}</td>
                <td class="p-3 text-sm text-gray-700">{{ $item->quantity }}</td>
                <td class="p-3 text-sm text-gray-700">{{ number_format($item->price, 2) }} د.ل</td>
                <td class="p-3 text-sm text-gray-700">{{ number_format($item->quantity * $item->price, 2) }} د.ل</td>
                <td class="p-3 text-sm text-gray-700">{{ $item->created_at->format('Y-m-d H:i') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="p-4 text-center text-gray-500">لا توجد بيانات.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- المخزون المتبقي -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
      <h3 class="text-lg font-semibold text-primary-700 mb-4">المخزون المتبقي</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($remainingStock as $product)
          <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
            <div class="flex justify-between items-center">
              <span class="font-medium">{{ $product->name }}</span>
              <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $product->quantity }} متبقي</span>
            </div>
            <div class="mt-2 h-2 bg-gray-200 rounded-full">
<div class="h-2 bg-blue-500 rounded-full" style="width: {{ min(100, ($product->quantity / 100) * 100) }}%"></div>

            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<script>
  function exportTableToCSV() {
    let csvContent = "\uFEFF";
    const headers = ['رقم الفاتورة', 'الطالب', 'المنتج', 'الكمية', 'السعر', 'المجموع', 'التاريخ'];
    csvContent += headers.join(",") + "\n";

    document.querySelectorAll("table tbody tr").forEach(row => {
      let rowData = [];
      row.querySelectorAll("td").forEach(td => {
        let text = td.innerText.replace(/,/g, "،").trim();
        rowData.push(text);
      });
      csvContent += rowData.join(",") + "\n";
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "تقرير_يومي.csv";
    link.click();
  }
</script>

</body>
</html>
