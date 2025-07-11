<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // قبل أي تعديل على البنية، نقوم بتحديث البيانات
        // ننقل user_id من جدول parents إلى الجداول الأخرى كقيمة مؤقتة قبل إعادة تسمية العمود
        DB::table('students')
            ->join('parents', 'students.parent_id', '=', 'parents.parent_id')
            ->update(['students.parent_id' => DB::raw('parents.user_id')]);

        DB::table('wallets')
            ->join('parents', 'wallets.parent_id', '=', 'parents.parent_id')
            ->update(['wallets.parent_id' => DB::raw('parents.user_id')]);

        DB::table('banned_products')
            ->join('parents', 'banned_products.parent_id', '=', 'parents.parent_id')
            ->update(['banned_products.parent_id' => DB::raw('parents.user_id')]);


        // تعديل جدول students
        Schema::table('students', function (Blueprint $table) {
            // حذف القيد القديم
            $table->dropForeign(['parent_id']);
            // إعادة تسمية العمود ليعكس العلاقة الجديدة
            $table->renameColumn('parent_id', 'user_id');
            // إضافة القيد الجديد الذي يربط بجدول users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // تعديل جدول wallets
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->renameColumn('parent_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // تعديل جدول banned_products
        Schema::table('banned_products', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->renameColumn('parent_id', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // أخيراً، حذف جدول parents بعد أن لم نعد بحاجة إليه
        Schema::dropIfExists('parents');
    }

    /**
     * Reverse the migrations.
     * (للتراجع في حال حدوث خطأ)
     */
    public function down(): void
    {
        // إعادة إنشاء جدول parents
        Schema::create('parents', function (Blueprint $table) {
            $table->id('parent_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        // إعادة الجداول الأخرى لحالتها السابقة
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'parent_id');
            $table->foreign('parent_id')->references('parent_id')->on('parents')->onDelete('cascade');
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'parent_id');
            $table->foreign('parent_id')->references('parent_id')->on('parents')->onDelete('cascade');
        });

        Schema::table('banned_products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->renameColumn('user_id', 'parent_id');
            $table->foreign('parent_id')->references('parent_id')->on('parents')->onDelete('cascade');
        });
    }
};