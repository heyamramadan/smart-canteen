<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>التقرير اليومي للمبيعات - لوحة تحكم المقصف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
        body {
            font-family: 'Tajawal', sans-serif;
        }
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        button {
            transition: background-color 0.3s ease;
        }
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

<div class="flex h-screen">
    @include('layouts.sidebar')
    <div class="flex-1 p-6 overflow-auto">
        <!-- رأس الصفحة -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6 flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <i class="fas fa-calendar-day ml-2"></i> التقرير اليومي للمبيعات
            </h2>
            <div class="text-gray-600 font-medium">التاريخ: 2025-07-04</div>
        </div>

        <!-- بطاقات الملخص -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-4 rtl:space-x-reverse">
                <div class="text-blue-600 text-3xl">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <h3 class="text-gray-500">إجمالي المبيعات</h3>
                    <p class="text-xl font-bold">15,250.00 ر.س</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-4 rtl:space-x-reverse">
                <div class="text-green-600 text-3xl">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h3 class="text-gray-500">عدد الطلبات</h3>
                    <p class="text-xl font-bold">120</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 flex items-center space-x-4 rtl:space-x-reverse">
                <div class="text-purple-600 text-3xl">
                    <i class="fas fa-box-open"></i>
                </div>
                <div>
                    <h3 class="text-gray-500">المنتجات المباعة</h3>
                    <p class="text-xl font-bold">450</p>
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
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 text-sm text-gray-700 font-medium">#ORD-101</td>
                            <td class="p-3 text-sm text-gray-700">محمد أحمد</td>
                            <td class="p-3 text-sm text-gray-700">عصير برتقال</td>
                            <td class="p-3 text-sm text-gray-700">2</td>
                            <td class="p-3 text-sm text-gray-700">5.00 د.ل</td>
                            <td class="p-3 text-sm text-gray-700">10.00 د.ل</td>
                            <td class="p-3 text-sm text-gray-700">2025-07-04 09:30</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 text-sm text-gray-700 font-medium">#ORD-102</td>
                            <td class="p-3 text-sm text-gray-700">سارة علي</td>
                            <td class="p-3 text-sm text-gray-700">سندويش دجاج</td>
                            <td class="p-3 text-sm text-gray-700">1</td>
                            <td class="p-3 text-sm text-gray-700">12.50 د.ل</td>
                            <td class="p-3 text-sm text-gray-700">12.50 د.ل</td>
                            <td class="p-3 text-sm text-gray-700">2025-07-04 10:15</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-3 text-sm text-gray-700 font-medium">#ORD-103</td>
                            <td class="p-3 text-sm text-gray-700">أحمد حسن</td>
                            <td class="p-3 text-sm text-gray-700">كيك شوكولاتة</td>
                            <td class="p-3 text-sm text-gray-700">3</td>
                            <td class="p-3 text-sm text-gray-700">7.00 د.ل</td>
                            <td class="p-3 text-sm text-gray-700">21.00 د.ل</td>
                            <td class="p-3 text-sm text-gray-700">2025-07-04 11:00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- المخزون المتبقي -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-primary-700 mb-4">المخزون المتبقي</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">عصير برتقال</span>
                        <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">40 متبقي</span>
                    </div>
                    <div class="mt-2 h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-500 rounded-full" style="width: 80%"></div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">سندويش دجاج</span>
                        <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">15 متبقي</span>
                    </div>
                    <div class="mt-2 h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-yellow-500 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">كيك شوكولاتة</span>
                        <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded-full">5 متبقي</span>
                    </div>
                    <div class="mt-2 h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-red-500 rounded-full" style="width: 10%"></div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">ماء معدني</span>
                        <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">50 متبقي</span>
                    </div>
                    <div class="mt-2 h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-500 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
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
                // تنقية النص من الفواصل
                let text = td.innerText.replace(/,/g, "،").trim();
                rowData.push(text);
            });
            csvContent += rowData.join(",") + "\n";
        });

        const blob = new Blob([csvContent], {type: 'text/csv;charset=utf-8;'});
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "تقرير_يومي_2025-07-04.csv";
        link.click();
    }
</script>

</body>
</html>
