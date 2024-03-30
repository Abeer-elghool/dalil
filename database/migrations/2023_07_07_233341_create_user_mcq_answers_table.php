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
        Schema::create('user_mcq_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_mcq_id')->nullable()->references('id')->on('user_mcqs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('mcq_id')->nullable()->references('id')->on('mcqs')->onDelete('cascade');
            $table->foreignId('question_id')->nullable()->references('id')->on('questions')->onDelete('cascade');
            $table->foreignId('answer_id')->nullable()->references('id')->on('answers')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->string('question_score')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mcq_answers');
    }
};
