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
        Schema::create('addon_upload_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_upload_id');
            $table->enum('type', ['web', 'ingame', 'update']);
            $table->string('ip_address', 45);
            $table->timestamps();

            $table->foreign('addon_upload_id')->references('id')->on('addon_uploads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_statistics');
    }
};
