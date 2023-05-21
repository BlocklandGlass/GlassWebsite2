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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_board_id');
            $table->foreignId('blid_id');
            $table->tinyText('name');
            $table->tinyText('summary');
            $table->text('description');
            $table->unsignedMediumInteger('total_downloads');
            $table->unsignedMediumInteger('web_downloads');
            $table->unsignedMediumInteger('ingame_downloads');
            $table->unsignedMediumInteger('update_downloads');
            $table->unsignedMediumInteger('legacy_total_downloads');
            $table->unsignedMediumInteger('legacy_web_downloads');
            $table->unsignedMediumInteger('legacy_ingame_downloads');
            $table->unsignedMediumInteger('legacy_update_downloads');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('addon_board_id')->references('id')->on('addon_boards');
            $table->foreign('blid_id')->references('id')->on('blids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
