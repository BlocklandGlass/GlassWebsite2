<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('primary_blid')->unique()->nullable()->after('steam_id');

            $table->foreign('primary_blid')->references('id')->on('blids');
        });

        $users = User::all();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_primary_blid_foreign');
            $table->dropColumn('primary_blid');
        });
    }
};
