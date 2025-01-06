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
            $table->unsignedMediumInteger('total_downloads')->default(0)->change();
            $table->unsignedMediumInteger('web_downloads')->default(0)->change();
            $table->unsignedMediumInteger('ingame_downloads')->default(0)->change();
            $table->unsignedMediumInteger('update_downloads')->default(0)->change();
            $table->unsignedMediumInteger('legacy_total_downloads')->default(0)->change();
            $table->unsignedMediumInteger('legacy_web_downloads')->default(0)->change();
            $table->unsignedMediumInteger('legacy_ingame_downloads')->default(0)->change();
            $table->unsignedMediumInteger('legacy_update_downloads')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addons', function (Blueprint $table) {
            $table->unsignedMediumInteger('total_downloads')->default(null)->change();
            $table->unsignedMediumInteger('web_downloads')->default(null)->change();
            $table->unsignedMediumInteger('ingame_downloads')->default(null)->change();
            $table->unsignedMediumInteger('update_downloads')->default(null)->change();
            $table->unsignedMediumInteger('legacy_total_downloads')->default(null)->change();
            $table->unsignedMediumInteger('legacy_web_downloads')->default(null)->change();
            $table->unsignedMediumInteger('legacy_ingame_downloads')->default(null)->change();
            $table->unsignedMediumInteger('legacy_update_downloads')->default(null)->change();
        });
    }
};
