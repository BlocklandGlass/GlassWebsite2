<?php

use App\Models\AddonBoardGroup;
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
        Schema::create('addon_board_groups', function (Blueprint $table) {
            $table->id();
            $table->tinyText('name')->unique();
            $table->timestamps();
        });

        $names = [
            'Functional',
            'Content',
            'Cosmetic',
            'World',
        ];

        foreach ($names as $name) {
            $addonBoardGroup = new AddonBoardGroup;
            $addonBoardGroup->name = $name;
            $addonBoardGroup->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_board_groups');
    }
};
