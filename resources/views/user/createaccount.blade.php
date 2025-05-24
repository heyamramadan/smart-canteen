<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <title>المقصف الذكي - إنشاء حساب</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans">

  <div class="min-h-screen flex flex-col justify-between">

    <!-- الشعار -->
    <div class="text-center py-4">
      <img src="{{ asset('images/logo.png') }}" alt="شعار" class="mx-auto w-40 mb-2">
    </div>

    <!-- المحتوى الرئيسي -->
    <div class="flex flex-col lg:flex-row-reverse items-center justify-center w-full px-6 lg:px-20 gap-10 flex-1">

      <!-- الصورة على اليمين -->
      <div class="w-full lg:w-1/2 flex justify-center">
        <img src="{{ asset('images/canteen.jpg') }}" alt="صورة المقصف" class="w-[90%] h-auto">
      </div>

      <!-- النموذج على اليسار -->
      <div class="w-full lg:w-1/2 max-w-lg text-right">
        <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
          @csrf

          <div>
            <label class="block text-sm text-gray-600 mb-1">البريد الإلكتروني</label>
            <input
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              class="w-full border border-orange-300 rounded-full px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">اسم المستخدم</label>
            <input
              type="text"
              name="username"
              value="{{ old('username') }}"
              required
              class="w-full border border-orange-300 rounded-full px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('username') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">الاسم الكامل</label>
            <input
              type="text"
              name="full_name"
              value="{{ old('full_name') }}"
              required
              class="w-full border border-orange-300 rounded-full px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('full_name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">الدور</label>
            <select
              name="role"
              required
              class="w-full border border-orange-300 rounded-full px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
              <option value="">اختر الدور</option>
              <option value="مسؤول" {{ old('role') == 'مسؤول' ? 'selected' : '' }}>مسؤول</option>
              <option value="موظف" {{ old('role') == 'موظف' ? 'selected' : '' }}>موظف</option>
              <option value="ولي أمر" {{ old('role') == 'ولي أمر' ? 'selected' : '' }}>ولي أمر</option>
            </select>
            @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">كلمة المرور</label>
            <input
              type="password"
              name="password"
              required
              class="w-full border border-orange-300 rounded-full px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">تأكيد كلمة المرور</label>
            <input
              type="password"
              name="password_confirmation"
              required
              class="w-full border border-orange-300 rounded-full px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
          </div>

          <div>
            <label class="block text-sm text-gray-600 mb-1">رقم الهاتف</label>
            <input
              type="text"
              name="phone_number"
              value="{{ old('phone_number') }}"
              class="w-full border border-orange-300 rounded-full px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
            @error('phone_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
          </div>

          <div class="pt-2">
            <button
              type="submit"
              class="w-full border border-orange-400 text-orange-500 py-2 rounded-full hover:bg-orange-400 hover:text-white transition"
            >
              إنشاء حساب
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

</body>
</html>
