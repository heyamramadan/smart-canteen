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
    Schema::create('wallets', function (Blueprint $table) {
        $table->bigIncrements('wallet_id');
        $table->unsignedBigInteger('parent_id');
        $table->decimal('balance', 10, 2)->default(0);
        $table->timestamps();

        // المفتاح الأجنبي يربط wallet بجدول parents
        $table->foreign('parent_id')->references('parent_id')->on('parents')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
