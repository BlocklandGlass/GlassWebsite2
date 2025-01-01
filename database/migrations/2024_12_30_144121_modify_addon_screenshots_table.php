<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE addon_screenshots CHANGE COLUMN display_order display_order DECIMAL(3, 1) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE addon_screenshots CHANGE COLUMN display_order display_order DECIMAL(3, 1) UNSIGNED NOT NULL');
    }
};
