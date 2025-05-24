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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->unsignedBigInteger('parent_id');
            $table->string('full_name');
             $table->string('father_name'); 
            $table->string('class');
            $table->timestamps();

            // مفتاح خارجي إلى جدول parents
            $table->foreign('parent_id')->references('parent_id')->on('parents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
