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
        Schema::create('addon_screenshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_id');
            $table->tinyText('file_path');
            $table->unsignedDecimal('display_order', 3, 1);
            $table->timestamps();

            $table->foreign('addon_id')->references('id')->on('addons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_screenshots');
    }
};
