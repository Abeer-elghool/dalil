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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->string('title')->nullable();
            $table->longText('body')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->string('file')->nullable();
            $table->string('link')->nullable();
            $table->string('views_count')->nullable()->default(0);
            $table->string('likes_count')->nullable()->default(0);
            $table->string('dislikes_count')->nullable()->default(0);
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins')->onDelete('cascade');
            $table->foreignId('article_category_id')->nullable()->references('id')->on('article_categories')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
