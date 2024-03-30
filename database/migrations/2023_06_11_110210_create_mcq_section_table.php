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
        Schema::create('mcq_section', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcq_id')->nullable()->references('id')->on('mcqs')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->references('id')->on('sections')->onDelete('cascade');
            $table->foreignId('chapter_id')->nullable()->references('id')->on('chapters')->onDelete('cascade');
            $table->integer('questions_count')->nullable()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcq_section');
    }
};
