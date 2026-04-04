<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('movies', function (Blueprint $table) {
            // First, set existing 0 ratings to NULL (since 0 means not rated)
            DB::statement('UPDATE movies SET rating = NULL WHERE rating = 0 OR rating IS NULL');
            
           $table->decimal('rating', 3, 1)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            DB::statement('UPDATE movies SET rating = 0 WHERE rating IS NULL');
            $table->decimal('rating', 3, 1)->default(0)->change();
        });
    }
};
