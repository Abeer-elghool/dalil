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
        Schema::create('latest_news', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->longText('title')->nullable();
            $table->longText('desc')->nullable();
            $table->string('slug')->nullable();
            $table->string('author_name')->nullable();
            $table->string('file')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->string('views_count')->nullable()->default(0);
            $table->string('downloads_count')->nullable()->default(0);
            $table->string('likes_count')->nullable()->default(0);
            $table->string('dislikes_count')->nullable()->default(0);
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latest_news');
    }
};
