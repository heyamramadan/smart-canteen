<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>تسجيل الدخول - المقصف الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans h-screen flex items-center justify-center">

    <div class="flex flex-col md:flex-row w-full md:w-[90%] lg:w-[75%] shadow-lg rounded-lg overflow-hidden border border-gray-200">

        <!-- Content Side -->
        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
            <div class="text-center mb-8">
                <div class="flex justify-center items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" class="w-24 h-24" alt="logo" />
                </div>
            </div>

            {{-- رسالة فشل تسجيل الدخول العامة --}}
            @if ($errors->has('login_error'))
                <div class="mb-4 text-center text-red-600 font-semibold bg-red-100 p-2 rounded">
                    {{ $errors->first('login_error') }}
                </div>
            @endif

            {{-- رسالة منع ولي الأمر --}}
            @if ($errors->has('unauthorized'))
                <div class="mb-4 text-center text-red-600 font-semibold bg-red-100 p-2 rounded">
                    {{ $errors->first('unauthorized') }}
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST" class="space-y-4">
                @csrf

                {{-- حقل اسم المستخدم --}}
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">اسم المستخدم</label>
                    <input
                        type="text"
                        name="username"
                        placeholder="اسم المستخدم"
                        class="w-full px-4 py-2 bg-white rounded-lg border @error('username') border-red-500 @else border-orange-500 @enderror focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-600"
                        value="{{ old('username') }}"
                    />
                    @error('username')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- حقل كلمة المرور --}}
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">كلمة المرور</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="كلمة المرور"
                        class="w-full px-4 py-2 bg-white rounded-lg border @error('password') border-red-500 @else border-orange-500 @enderror focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-600"
                    />
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6"></div>

                <button
                    type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-lg transition"
                >
                    تسجيل الدخول
                </button>
            </form>
        </div>

        <!-- Image Side -->
        <div class="hidden md:flex md:w-1/2 bg-white items-end justify-center p-4">
            <img src="{{ asset('images/smart.jpg') }}" alt="Kids eating" class="max-h-[350px]" />
        </div>
    </div>

</body>
</html>
