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
    Schema::create('order_items', function (Blueprint $table) {
        $table->bigIncrements('order_item_id');   // المفتاح الأساسي
        $table->unsignedBigInteger('order_id');  // مفتاح أجنبي لجدول الطلبات
        $table->unsignedBigInteger('product_id'); // مفتاح أجنبي لجدول المنتجات
        $table->integer('quantity');              // كمية المنتج في هذا الطلب
        $table->decimal('price', 10, 2);          // سعر المنتج وقت الطلب
        $table->timestamps();                      // حقول created_at و updated_at تلقائيًا

        // تعريف المفاتيح الأجنبية
        $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
