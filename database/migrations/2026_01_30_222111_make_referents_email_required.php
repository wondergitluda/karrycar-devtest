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
        Schema::table('referents', function (Blueprint $table) {
            // First, delete any referents with null email (cleanup)
            \Illuminate\Support\Facades\DB::table('referents')
                ->whereNull('email')
                ->delete();

            // Make email required (no unique constraint to allow seeder duplicates)
            $table->string('email')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referents', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }
};
