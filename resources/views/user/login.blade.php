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

            <form action="#" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">اسم المستخدم</label>
                  <input
                  type="text"
                   name="username"
                     placeholder="اسم المستخدم"
                  class="w-full px-4 py-2 bg-white rounded-lg border border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-600"
                    />

                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">كلمة المرور</label>
                    <input
                        type="password"
                        placeholder="كلمة المرور"
                        class="w-full px-4 py-2 bg-white  rounded-lg border border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-600"
                    />
                </div>

                <div class="text-sm text-center text-orange-500 mb-6">
                    <a href="#" class="hover:underline">نسيت كلمة المرور؟</a>
                </div>

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
