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
        Schema::create('protocols', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('desc')->nullable();
            $table->string('slug')->nullable();
            $table->bigInteger('admin_id')->unsigned()->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned()->nullable();
            $table->bigInteger('book_id')->unsigned()->nullable();
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->bigInteger('section_id')->unsigned()->nullable();
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->bigInteger('chapter_id')->unsigned()->nullable();
            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            $table->bigInteger('lesson_id')->unsigned()->nullable();
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            $table->foreignId('protocol_category_id')->references('id')->on('protocol_categories')->onDelete('cascade');
            $table->boolean('active')->nullable()->default(true);
            $table->string('file')->nullable();
            $table->string('views_count')->nullable()->default(0);
            $table->string('downloads_count')->nullable()->default(0);
            $table->string('likes_count')->nullable()->default(0);
            $table->string('dislikes_count')->nullable()->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('protocols');
    }
};
