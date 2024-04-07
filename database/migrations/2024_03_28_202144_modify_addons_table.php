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
        Schema::table('addons', function (Blueprint $table) {
            $table->foreignId('addon_board_id')->nullable()->change();
            $table->tinyText('summary')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->boolean('is_draft')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->foreignId('addon_board_id')->nullable(false)->change();
            $table->tinyText('summary')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->dropColumn('is_draft');
        });
    }
};
