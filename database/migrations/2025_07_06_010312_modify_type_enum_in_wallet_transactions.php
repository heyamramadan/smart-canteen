<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // الخطوة 1: إضافة عمود جديد مؤقت
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->enum('new_type', ['إيداع', 'سحب'])->nullable()->after('type');
        });

        // الخطوة 2: نسخ القيم من العمود القديم إلى الجديد مع الترجمة
        DB::table('wallet_transactions')->update([
            'new_type' => DB::raw("CASE
                WHEN type = 'deposit' THEN 'إيداع'
                WHEN type = 'withdraw' THEN 'سحب'
                ELSE NULL END")
        ]);

        // الخطوة 3: حذف العمود القديم
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        // الخطوة 4: إعادة تسمية العمود الجديد
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->renameColumn('new_type', 'type');
        });
    }

    public function down(): void
    {
        // لإرجاع التغيير عند التراجع (rollback)
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->enum('old_type', ['deposit', 'withdraw'])->nullable()->after('type');
        });

        DB::table('wallet_transactions')->update([
            'old_type' => DB::raw("CASE
                WHEN type = 'إيداع' THEN 'deposit'
                WHEN type = 'سحب' THEN 'withdraw'
                ELSE NULL END")
        ]);

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->renameColumn('old_type', 'type');
        });
    }
};

