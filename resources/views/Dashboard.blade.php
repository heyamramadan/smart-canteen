<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>لوحة تحكم إدارة المقصف</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- إضافة مكتبة الرسوم البيانية Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<div class="fixed top-0 right-0 h-screen w-64 bg-gradient-to-b from-primary-700 to-primary-600 text-white p-4 overflow-y-auto">
    <h2 class="text-xl font-bold mb-8 text-center pt-4">إدارة المقصف</h2>

    <ul class="space-y-3">
        <li class="p-3 hover:bg-primary-500 rounded-lg transition">
            <a href="{{ url('/profile') }}" class="flex items-center">
                <span class="ml-2">👤</span>
                الملف الشخصي
            </a>
        </li>

        @auth
            @if(auth()->user()->role === 'مسؤول')
                <li class="p-3 {{ request()->is('index*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/index') }}" class="flex items-center">
                        <span class="ml-2">👥</span>
                        إدارة المستخدمين
                    </a>
                </li>
                <li class="p-3 {{ request()->is('students*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/students') }}" class="flex items-center">
                        <span class="ml-2">🎒</span>
                        إدارة الطلاب
                    </a>
                </li>
                <li class="p-3 {{ request()->is('products*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/products') }}" class="flex items-center">
                        <span class="ml-2">🛒</span>
                        إدارة المنتجات
                    </a>
                </li>
                <li class="p-3 {{ request()->is('categories*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/categories') }}" class="flex items-center">
                        <span class="ml-2">📂</span>
                        إدارة التصنيفات
                    </a>
                </li>
                <li class="p-3 {{ request()->is('wallet*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/wallet') }}" class="flex items-center">
                        <span class="ml-2">💰</span>
                        شحن المحفظة
                    </a>
                </li>
                <li class="p-3 {{ request()->is('cards*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/cards') }}" class="flex items-center">
                        <span class="ml-2">💳</span>
                        إصدار بطاقة إلكترونية
                    </a>
                </li>
            @endif

            <li class="p-3 {{ request()->is('point*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                <a href="{{ url('/point') }}" class="flex items-center">
                    <span class="ml-2">🧾</span>
                    المبيعات
                </a>
            </li>
            <li class="p-3 {{ request()->is('invoices*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                <a href="{{ url('/invoices') }}" class="flex items-center">
                    <span class="ml-2">📄</span>
                    الفواتير
                </a>
            </li>

            @if(auth()->user()->role === 'مسؤول')
                <li class="p-3 {{ request()->is('reports*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/reports') }}" class="flex items-center">
                        <span class="ml-2">📊</span>
                        التقارير
                    </a>
                </li>
            @endif

            <li class="p-3 hover:bg-primary-500 rounded-lg transition cursor-pointer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full text-white text-right">
                        <span class="ml-2">🚪</span>
                        تسجيل خروج
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</div>

<div class="mr-64 p-6 overflow-auto min-h-screen bg-gray-50">
    @if(auth()->user()->role === 'مسؤول')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-blue-500">
                <h3 class="text-gray-500 text-sm">عدد الطلاب</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $studentCount ?? 0 }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-primary-500">
                <h3 class="text-gray-500 text-sm">أولياء الأمور</h3>
                <p class="text-2xl font-bold text-primary-600">{{ $parentCount ?? 0 }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-green-500">
                <h3 class="text-gray-500 text-sm">موظفي المقصف</h3>
                <p class="text-2xl font-bold text-green-600">{{ $employeeCount ?? 0 }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-lg border-l-4 border-purple-500">
                <h3 class="text-gray-500 text-sm">إجمالي المبيعات</h3>
                {{-- تنسيق الرقم كعملة --}}
                <p class="text-2xl font-bold text-purple-600">{{ number_format($totalSales ?? 0, 2) }} د.ل</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-lg text-gray-700 mb-4">المنتجات الأكثر مبيعاً</h3>
                <canvas id="topProductsChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="font-bold text-lg text-gray-700 mb-4">الطلاب الأكثر شراءً</h3>
                <canvas id="topStudentsChart"></canvas>
            </div>
        </div>
    @endif
</div>
<img src="/images/canteen.jpg" alt="شعار المقصف" class="fixed bottom-0 left-0 mb-4 ml-4 w-48 h-auto z-50" />

{{-- السكربت الخاص بإنشاء الرسوم البيانية --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- الرسم البياني للمنتجات الأكثر مبيعاً ---
        const topProductsData = @json($topProducts ?? []);
        if (topProductsData.length > 0) {
            const productLabels = topProductsData.map(item => item.product.name);
            const productQuantities = topProductsData.map(item => item.total_quantity);
            const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
            new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: productLabels,
                    datasets: [{
                        label: 'الكمية المباعة',
                        data: productQuantities,
                        backgroundColor: 'rgba(249, 115, 22, 0.5)',
                        borderColor: 'rgba(249, 115, 22, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'الكمية' } } },
                    plugins: { legend: { display: false }, title: { display: true, text: 'أفضل 5 منتجات مبيعاً' } }
                }
            });
        }

        // --- الرسم البياني للطلاب الأكثر شراءً ---
        const topStudentsData = @json($topStudents ?? []);
        if (topStudentsData.length > 0) {
            const studentLabels = topStudentsData.map(item => item.student.full_name);
            const studentSpending = topStudentsData.map(item => item.total_spent);
            const topStudentsCtx = document.getElementById('topStudentsChart').getContext('2d');
            new Chart(topStudentsCtx, {
                type: 'bar',
                data: {
                    labels: studentLabels,
                    datasets: [{
                        label: 'إجمالي الإنفاق (د.ل)',
                        data: studentSpending,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // جعل الأعمدة أفقية لتسهيل القراءة
                    responsive: true,
                    scales: { x: { beginAtZero: true, title: { display: true, text: 'المبلغ (د.ل)' } } },
                    plugins: { legend: { display: false }, title: { display: true, text: 'أفضل 5 طلاب إنفاقاً' } }
                }
            });
        }
    });
</script>

</body>
</html>