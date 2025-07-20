<div class="fixed h-screen w-64 bg-gradient-to-b from-primary-700 to-primary-600 text-white p-4 overflow-y-auto">
    <h2 class="text-xl font-bold mb-8 text-center pt-4">إدارة المقصف</h2>

    <ul class="space-y-3">
        <!-- الملف الشخصي: يظهر للجميع -->
        <li class="p-3 hover:bg-primary-500 rounded-lg transition">
            <a href="{{ url('/profile') }}" class="flex items-center">
                <span class="ml-2">👨‍💼 </span>
                الملف الشخصي
            </a>
        </li>
<!-- لوحة التحكم: تظهر بعد الملف الشخصي -->
<li class="p-3 {{ request()->is('dashboard') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
    <a href="{{ url('/dashboard') }}" class="flex items-center">
        <span class="ml-2">🏠</span>
        الصفحة الرئيسية
    </a>
</li>
        @auth
            @if(auth()->user()->role === 'مسؤول')
                <!-- صلاحيات المسؤول الكاملة -->
                <li class="p-3 {{ request()->is('index*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/index') }}" class="flex items-center">
                        <span class="ml-2">👥</span>
                        إدارة المستخدمين
                    </a>
                </li>
                <li class="p-3 {{ request()->is('archived-users*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
    <a href="{{ url('/archived-users') }}" class="flex items-center">
        <span class="ml-2">🗃️</span>
        أرشيف المستخدمين
    </a>
</li>

                <li class="p-3 {{ request()->is('students*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/students') }}" class="flex items-center">
                        <span class="ml-2">👨‍🎓</span>
                        إدارة الطلاب
                    </a>
                </li>
                <li class="p-3 {{ request()->is('archived-students*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
    <a href="{{ url('/archived-students') }}" class="flex items-center">
        <span class="ml-2">📦</span>
        أرشيف الطلاب
    </a>
</li>

                <li class="p-3 {{ request()->is('categories*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/categories') }}" class="flex items-center">
                        <span class="ml-2">📂</span>
                        إدارة التصنيفات
                    </a>
                </li>
                    <li class="p-3 {{ request()->is('products*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/products') }}" class="flex items-center">
                        <span class="ml-2">🛒</span>
                        إدارة المنتجات
                    </a>
                </li>
                <li class="p-3 {{ request()->is('wallet*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/wallet') }}" class="flex items-center">
                        <span class="ml-2">💰</span>
                        شحن المحفظة
                    </a>
                </li>
                 <li class="p-3 {{ request()->is('transactions*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/transactions') }}" class="flex items-center">
                        <span class="ml-2">💸</span>
                        سجل المعاملات
                    </a>
                </li>
                <li class="p-3 {{ request()->is('cards*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/cards') }}" class="flex items-center">
                        <span class="ml-2">💳</span>
                        إصدار بطاقة إلكترونية
                    </a>
                </li>
            @endif
  @if(auth()->user()->role === 'موظف')
                <!-- رابط التقرير اليومية يظهر فقط للموظف -->
                <li class="p-3 {{ request()->is('daily-report*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/daily-report') }}" class="flex items-center">
                        <span class="ml-2">📅</span>
                        التقرير اليومية
                    </a>
                </li>
            @endif
            @if(in_array(auth()->user()->role, ['مسؤول', 'موظف']))
                <!-- المبيعات: تظهر للمسؤول والموظف -->
                <li class="p-3 {{ request()->is('point*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/point') }}" class="flex items-center">
                        <span class="ml-2">🧾</span>
                        المبيعات
                    </a>
                </li>
                <li class="p-3 {{ request()->is('invoices*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ route('invoices.index') }}" class="flex items-center">
                        <span class="ml-2">📃</span>
                        الفواتير
                    </a>
                </li>

            @endif

            @if(auth()->user()->role === 'مسؤول')
                <!-- التقارير والإعدادات تظهر للمسؤول فقط -->
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
        <button type="submit" class="flex items-center w-full text-white">
            <span class="ml-2">🚪</span>
            تسجيل خروج
        </button>
    </form>
</li>

        @endauth
    </ul>
</div>

<!-- أضف هامشًا للمحتوى الرئيسي لتعويض الشريط الجانبي الثابت -->
<div class="ml-64">
    <!-- محتوى الصفحة الرئيسي هنا -->
</div>
