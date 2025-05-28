<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين - لوحة تحكم المقصف</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        body { font-family: 'Tajawal', sans-serif; }

        @keyframes fade-in-out {
  0%, 100% { opacity: 0; transform: translateY(-10px); }
  10%, 90% { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-out {
  animation: fade-in-out 3s ease-in-out forwards;
}

    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        @include('layouts.sidebar')

        <div class="flex-1 p-6 overflow-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4">
                <h2 class="text-lg font-bold text-primary-700 flex items-center">
                    <span class="ml-2">🗄️</span>
                    الأرشيف - المستخدمين المحذوفين
                </h2>
            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-right text-sm text-gray-500">اسم المستخدم</th>
                                <th class="p-3 text-right text-sm text-gray-500">الاسم الكامل</th>
                                <th class="p-3 text-right text-sm text-gray-500">البريد الإلكتروني</th>
                                <th class="p-3 text-right text-sm text-gray-500">الدور</th>
                                <th class="p-3 text-right text-sm text-gray-500">تاريخ الحذف</th>
                                <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($archivedUsers as $user)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 text-sm">{{ $user->username }}</td>
                                    <td class="p-3 text-sm">{{ $user->full_name }}</td>
                                    <td class="p-3 text-sm">{{ $user->email }}</td>
                                    <td class="p-3 text-sm">{{ $user->role }}</td>
                                    <td class="p-3 text-sm">{{ $user->deleted_at->format('Y-m-d') }}</td>
                                    <td class="p-3 flex items-center">
                                        <form action="{{ route('users.restore', $user->user_id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-500 hover:text-green-700 mx-1 p-1 rounded hover:bg-green-100 transition">
                                                🔄 استعادة
                                            </button>
                                        </form>
                                        <form action="{{ route('users.force-delete', $user->user_id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 mx-1 p-1 rounded hover:bg-red-100 transition">
                                                🗑️ حذف نهائي
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-start">
                <a href="{{ route('users.index') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                    ← العودة إلى قائمة المستخدمين
                </a>
            </div>
        </div>
    </div>


</body>
</html>