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
        Schema::create('user_mcqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcq_id')->nullable()->references('id')->on('mcqs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('questions_count')->default(0);
            $table->string('answered_questions_count')->default(0);
            $table->string('user_score')->default(0);
            $table->enum('status', ['in_progress', 'finished']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mcqs');
    }
};
