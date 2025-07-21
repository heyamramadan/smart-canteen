<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('pin_code', 6)->after('image_path'); // رقم سري من 6 أرقام
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('pin_code');
        });
    }
};
