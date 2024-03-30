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
            $table->string('score')->default(0);
            $table->string('question_score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mcqs', function (Blueprint $table) {
            $table->dropColumn('question_score', 'score');
        });
    }
};
