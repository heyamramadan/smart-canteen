<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
class BackupController extends Controller
{
    public function create()
    {
        try {
            // بدء عملية النسخ الاحتياطي (قاعدة البيانات فقط)
            Artisan::call('backup:run', ['--only-db' => true]);

            // (اختياري) العثور على أحدث ملف نسخة احتياطية وتوفيره للتنزيل
            $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
            $files = $disk->files(config('backup.backup.name'));
            $latestBackup = collect($files)->sortByDesc(function ($file) use ($disk) {
                return $disk->lastModified($file);
            })->first();

            if ($latestBackup) {
                // قم بتنزيل الملف ثم حذفه (أو يمكنك الاحتفاظ به)
                return $disk->download($latestBackup);
            }

            return back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح، ولكن لم يتم العثور على الملف للتنزيل.');

        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return back()->with('error', 'حدث خطأ أثناء إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }
}

