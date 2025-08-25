<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>المقصف الذكي</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
    }
  </style>
</head>
<body class="bg-white min-h-screen flex flex-col items-center justify-start">

  <div class="w-full max-w-[1280px]">

    <div class="relative">
      <div class="h-[320px] rounded-b-[100px] relative z-0 bg-gradient-to-r from-[#FA9533] to-[#e17e20]"></div>

      <div class="absolute top-[260px] left-1/2 transform -translate-x-1/2 z-10 text-center w-full px-4">
        <div class="bg-white shadow-xl p-5 rounded-full w-28 h-28 mx-auto flex items-center justify-center mb-4">
          <img src="{{ asset('images/logo.png') }}" alt="شعار" class="w-24 h-24 object-contain" />
        </div>
        <h1 class="text-3xl font-bold text-gray-700 leading-tight">
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FA9533] to-[#e17e20]">المقصف</span> الذكي
        </h1>
      </div>
    </div>

    <div class="mt-40 flex flex-col items-center gap-6 w-full max-w-sm mx-auto px-4">
      <a href="/login" class="block w-full text-center bg-[#FA9533] text-white py-3 rounded-full shadow-lg hover:bg-[#e17e20] transition transform hover:scale-105 text-lg">
        تسجيل الدخول
      </a>
    </div>

  </div>

</body>
</html>
