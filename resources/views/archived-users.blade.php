{{-- resources/views/users/archived-users.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>سجل الأرشيف - لوحة تحكم المقصف</title>
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
    </style>
</head>
<body class="bg-gray-50">
<div class="flex h-screen">
    @include('layouts.sidebar')

    <div class="flex-1 p-6 overflow-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4 flex justify-between items-center">
            <h2 class="text-lg font-bold text-primary-700 flex items-center">
                <span class="ml-2">📁</span>
                سجل أرشيف المستخدمين
            </h2>
            <a href="{{ route('users.index') }}"
               class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                🔙 العودة
            </a>
        </div>

        <!-- جدول المستخدمين المؤرشفين -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-right text-sm text-gray-500">اسم المستخدم</th>
                        <th class="p-3 text-right text-sm text-gray-500">الاسم الكامل</th>
                        <th class="p-3 text-right text-sm text-gray-500">البريد الإلكتروني</th>
                        <th class="p-3 text-right text-sm text-gray-500">رقم الهاتف</th>
                        <th class="p-3 text-right text-sm text-gray-500">الدور</th>
                        <th class="p-3 text-right text-sm text-gray-500">تاريخ التسجيل</th>
                        <th class="p-3 text-right text-sm text-gray-500">الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    @forelse ($archivedUsers as $user)
                        <tr class="bg-gray-50 text-gray-500 hover:bg-gray-100 transition">
                            <td class="p-3 text-sm">{{ $user->username }}</td>
                            <td class="p-3 text-sm">{{ $user->full_name }}</td>
                            <td class="p-3 text-sm">{{ $user->email }}</td>
                            <td class="p-3 text-sm">{{ $user->phone_number }}</td>
                            <td class="p-3 text-sm">{{ $user->role }}</td>
                            <td class="p-3 text-sm">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="p-3">
                                <form method="POST" action="{{ route('users.restore', $user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-green-600 border border-green-600 px-3 py-1 rounded-lg hover:bg-green-600 hover:text-white transition">
                                        ♻️ استعادة
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">لا يوجد مستخدمون مؤرشفون حالياً.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="mt-4 px-4">
                    {{ $archivedUsers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
