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
        Schema::table('mcqs', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('views_count')->nullable()->default(0);
            $table->string('likes_count')->nullable()->default(0);
            $table->string('dislikes_count')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mcqs', function (Blueprint $table) {
            $table->dropColumn('slug', 'views_count', 'likes_count', 'dislikes_count');
        });
    }
};
