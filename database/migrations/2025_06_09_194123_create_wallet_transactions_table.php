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
    Schema::create('wallet_transactions', function (Blueprint $table) {
        $table->bigIncrements('transaction_id');
        $table->unsignedBigInteger('wallet_id');
        $table->decimal('amount', 10, 2);
        $table->enum('type', ['deposit', 'withdraw']);
        $table->string('reference')->nullable();
        $table->timestamp('created_at')->nullable();

        $table->foreign('wallet_id')->references('wallet_id')->on('wallets')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
