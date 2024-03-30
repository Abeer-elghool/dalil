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
        Schema::create('protocol_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->string('title');
            $table->longText('desc')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->string('file')->nullable();
            $table->string('views_count')->nullable()->default(0);
            $table->string('downloads_count')->nullable()->default(0);
            $table->string('likes_count')->nullable()->default(0);
            $table->string('dislikes_count')->nullable()->default(0);
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins')->onDelete('cascade');
            $table->foreignId('book_id')->nullable()->references('id')->on('books')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->references('id')->on('sections')->onDelete('cascade');
            $table->foreignId('chapter_id')->nullable()->references('id')->on('chapters')->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->references('id')->on('lessons')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocol_categories');
    }
};
