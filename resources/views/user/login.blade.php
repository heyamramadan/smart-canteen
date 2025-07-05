<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>تسجيل الدخول - المقصف الذكي</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans h-screen flex items-center justify-center relative">

    <!-- ✅ مودال رسائل الخطأ -->
    @if ($errors->has('login_error') || $errors->has('unauthorized'))
        <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 text-center">
                <svg class="w-12 h-12 mx-auto text-red-500 mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 15a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm.93-11.412l.588 5.587a1 1 0 01-.998 1.112h-1.04a1 1 0 01-.998-1.112l.588-5.587a1 1 0 011.96 0z"/>
                </svg>
                <h2 class="text-lg font-semibold text-red-600 mb-2">حدث خطأ</h2>
                <p class="text-gray-700 mb-4">
                    {{ $errors->first('login_error') == 'اسم المستخدم أو كلمة المرور غير صحيحة'
                        ? 'اسم المستخدم أو كلمة المرور غير صحيحة'
                        : 'غير مسموح لأولياء الأمور بالدخول للنظام' }}
                </p>
                <button onclick="closeModal()" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg focus:outline-none">
                    موافق
                </button>
            </div>
        </div>
    @endif

    <!-- ✅ نموذج تسجيل الدخول -->
    <div class="flex flex-col md:flex-row w-full md:w-[90%] lg:w-[75%] shadow-lg rounded-lg overflow-hidden border border-gray-200 z-10">

        <!-- Content Side -->
        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
            <div class="text-center mb-8">
                <div class="flex justify-center items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" class="w-24 h-24" alt="logo" />
                </div>
            </div>

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

    <!-- ✅ سكريبت إغلاق المودال -->
    <script>
        function closeModal() {
            document.getElementById("errorModal").style.display = "none";
        }
    </script>

</body>
</html>
