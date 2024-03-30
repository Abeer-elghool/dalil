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
        Schema::table('sections', function (Blueprint $table) {
            $table->boolean('elastic_search')->default(0);
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->boolean('elastic_search')->default(0);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->boolean('elastic_search')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('elastic_search');
        });
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn('elastic_search');
        });
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('elastic_search');
        });
    }
};
