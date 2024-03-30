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
        Schema::create('mcq_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcq_id')->nullable()->references('id')->on('mcqs')->onDelete('cascade');
            $table->foreignId('question_id')->nullable()->references('id')->on('questions')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcq_question');
    }
};
