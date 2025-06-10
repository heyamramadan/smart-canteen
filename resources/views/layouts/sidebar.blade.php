<!-- الشريط الجانبي -->
<div class="w-64 bg-gradient-to-b from-primary-700 to-primary-600 text-white p-4">
    <h2 class="text-xl font-bold mb-8 text-center pt-4">إدارة المقصف</h2>

    <ul class="space-y-3">

        <!-- الملف الشخصي: يظهر للجميع -->
        <li class="p-3 hover:bg-primary-500 rounded-lg transition">
            <a href="#" class="flex items-center">
                <span class="ml-2">👤</span>
                الملف الشخصي
            </a>
        </li>

        @auth
            @if(auth()->user()->role === 'مسؤول')
                <!-- إدارة المستخدمين -->
                <li class="p-3 {{ request()->is('index*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/index') }}" class="flex items-center">
                        <span class="ml-2">👥</span>
                        إدارة المستخدمين
                    </a>
                </li>

                <!-- إدارة الطلاب -->
                <li class="p-3 {{ request()->is('students*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/students') }}" class="flex items-center">
                        <span class="ml-2">🎒</span>
                        إدارة الطلاب
                    </a>
                </li>

                <!-- إدارة المنتجات -->
                <li class="p-3 {{ request()->is('products*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/products') }}" class="flex items-center">
                        <span class="ml-2">🛒</span>
                        إدارة المنتجات
                    </a>
                </li>

                <!-- إدارة التصنيفات -->
                <li class="p-3 {{ request()->is('categories*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
                    <a href="{{ url('/categories') }}" class="flex items-center">
                        <span class="ml-2">📂</span>
                        إدارة التصنيفات
                    </a>
                </li>
                <!-- شحن محفظة أولياء الأمور -->
<li class="p-3 {{ request()->is('wallet*') ? 'bg-primary-500' : 'hover:bg-primary-500' }} rounded-lg transition">
    <a href="{{ route('wallet') }}" class="flex items-center">
        <span class="ml-2">💰</span>
        شحن المحفظة
    </a>
</li>

                <!-- البطاقات الإلكترونية -->
                <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                    <a href="#" class="flex items-center">
                        <span class="ml-2">💳</span>
                        البطاقات الإلكترونية
                    </a>
                </li>

                <!-- التقارير -->
                <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                    <a href="#" class="flex items-center">
                        <span class="ml-2">📈</span>
                        التقارير
                    </a>
                </li>

                <!-- الإعدادات -->
                <li class="p-3 hover:bg-primary-500 rounded-lg transition">
                    <a href="#" class="flex items-center">
                        <span class="ml-2">⚙️</span>
                        إعدادات النظام
                    </a>
                </li>
            @elseif(auth()->user()->role === 'موظف')


            @endif
        @endauth

    </ul>
</div>
