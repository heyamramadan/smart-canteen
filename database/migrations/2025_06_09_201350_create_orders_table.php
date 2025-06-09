<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->bigIncrements('order_id');          // معرف الفاتورة (مفتاح أساسي)
        $table->unsignedBigInteger('student_id');  // معرف الطالب (مفتاح أجنبي)
        $table->unsignedBigInteger('employee_id'); // معرف الموظف (مفتاح أجنبي)
        $table->decimal('total_amount', 10, 2);    // المبلغ الكلي للفاتورة
        $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending'); // حالة الطلب
        $table->text('rejection_reason')->nullable(); // سبب الرفض إذا تم رفض الفاتورة
        $table->timestamps();                       // حقول created_at و updated_at

        // تعريف المفاتيح الأجنبية وربطها بالجداول الأخرى
        $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
        $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
