<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// جدولة أمر النسخ الاحتياطي يوميًا الساعة 00:00
Schedule::command('backup:run')->daily()->at('00:00');

// جدولة أمر تنظيف النسخ القديمة يوميًا الساعة 01:00
Schedule::command('backup:clean')->daily()->at('01:00');
