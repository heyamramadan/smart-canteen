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
    Schema::create('products', function (Blueprint $table) {
        $table->bigIncrements('product_id');
        $table->unsignedBigInteger('category_id');
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2);
         $table->integer('quantity')->default(0);
        $table->string('image')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();

        // العلاقة مع جدول categories
        $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
