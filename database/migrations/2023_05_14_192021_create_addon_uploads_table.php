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
        Schema::create('addon_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_id');
            $table->tinyText('file_name');
            $table->unsignedInteger('file_size');
            $table->tinyText('file_path');
            $table->tinyText('version');
            $table->boolean('restart_required');
            $table->text('changelog');
            $table->enum('review_status', ['pending', 'approved', 'rejected']);
            $table->text('review_comment');
            $table->foreignId('reviewer_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->unsignedMediumInteger('total_downloads');
            $table->unsignedMediumInteger('web_downloads');
            $table->unsignedMediumInteger('ingame_downloads');
            $table->unsignedMediumInteger('update_downloads');
            $table->timestamps();

            $table->foreign('addon_id')->references('id')->on('addons');
            $table->foreign('reviewer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_uploads');
    }
};
