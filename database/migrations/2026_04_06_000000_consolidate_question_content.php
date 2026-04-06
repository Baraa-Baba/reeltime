<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Drop the specific columns
            $table->dropColumn(['emoji', 'character', 'quote', 'scene']);
            // Add a generic content column
            $table->text('content')->nullable()->after('correct_answer');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Restore the specific columns
            $table->dropColumn('content');
            $table->string('emoji')->nullable()->after('correct_answer');
            $table->string('character')->nullable()->after('emoji');
            $table->text('quote')->nullable()->after('character');
            $table->text('scene')->nullable()->after('quote');
        });
    }
};
