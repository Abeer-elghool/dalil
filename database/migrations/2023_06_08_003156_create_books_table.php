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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('desc')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->string('file')->nullable();
            $table->string('views_count')->nullable()->default(0);
            $table->string('downloads_count')->nullable()->default(0);
            $table->string('likes_count')->nullable()->default(0);
            $table->string('dislikes_count')->nullable()->default(0);
            $table->bigInteger('admin_id')->unsigned()->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('book_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('book_id')->unsigned();

            $table->string('locale')->index();
            $table->unique(['book_id','locale']);
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_translations');
        Schema::dropIfExists('books');
    }
};
