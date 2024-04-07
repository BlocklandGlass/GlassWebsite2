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
        Schema::create('addon_upload_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_upload_id');
            $table->foreignId('blid_id');
            $table->text('body');
            $table->timestamps();

            $table->foreign('addon_upload_id')->references('id')->on('addon_uploads');
            $table->foreign('blid_id')->references('id')->on('blids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_upload_comments');
    }
};
