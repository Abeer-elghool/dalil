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
        Schema::create('saved_parts', function (Blueprint $table) {
            $table->id();
            $table->longText('content')->nullable();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('book_id')->nullable()->references('id')->on('books')->onDelete('cascade');
            $table->foreignId('chapter_id')->nullable()->references('id')->on('chapters')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->references('id')->on('sections')->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->references('id')->on('lessons')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_parts');
    }
};
