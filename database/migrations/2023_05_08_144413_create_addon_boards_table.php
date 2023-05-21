<?php

use App\Models\AddonBoard;
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
        Schema::create('addon_boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_board_group_id');
            $table->tinyText('name')->unique();
            $table->tinyText('icon')->unique();
            $table->timestamps();

            $table->foreign('addon_board_group_id')->references('id')->on('addon_board_groups');
        });

        $boards = [
            [
                'addon_board_group_id' => 1,
                'name' => 'Server Mods',
                'icon' => 'script_code',
            ],
            [
                'addon_board_group_id' => 1,
                'name' => 'Client Mods',
                'icon' => 'script_code_red',
            ],
            [
                'addon_board_group_id' => 1,
                'name' => 'Gamemodes',
                'icon' => 'board_game',
            ],
            [
                'addon_board_group_id' => 1,
                'name' => 'Clients',
                'icon' => 'new_window',
            ],
            [
                'addon_board_group_id' => 1,
                'name' => 'Support Mods',
                'icon' => 'support',
            ],
            [
                'addon_board_group_id' => 1,
                'name' => 'Events',
                'icon' => 'lightning',
            ],
            [
                'addon_board_group_id' => 1,
                'name' => 'Gamemode Maps',
                'icon' => 'world',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Bricks',
                'icon' => 'brick',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Weapons',
                'icon' => 'gun',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Items',
                'icon' => 'box_closed',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Tools',
                'icon' => 'toolbox',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Playertypes',
                'icon' => 'user_add',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Vehicles',
                'icon' => 'car',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Novelty',
                'icon' => 'party_hat',
            ],
            [
                'addon_board_group_id' => 2,
                'name' => 'Bargain Bin',
                'icon' => 'bin_empty',
            ],
            [
                'addon_board_group_id' => 3,
                'name' => 'Colorsets',
                'icon' => 'color_swatch',
            ],
            [
                'addon_board_group_id' => 3,
                'name' => 'Decals',
                'icon' => 't_shirt_print',
            ],
            [
                'addon_board_group_id' => 3,
                'name' => 'Prints',
                'icon' => 'blueprint',
            ],
            [
                'addon_board_group_id' => 3,
                'name' => 'Sounds',
                'icon' => 'sound',
            ],
            [
                'addon_board_group_id' => 3,
                'name' => 'Lights',
                'icon' => 'bulb',
            ],
            [
                'addon_board_group_id' => 3,
                'name' => 'Emotes',
                'icon' => 'emotion_cool',
            ],
            [
                'addon_board_group_id' => 3,
                'name' => 'Emitters',
                'icon' => 'fire',
            ],
            [
                'addon_board_group_id' => 4,
                'name' => 'Environments',
                'icon' => 'photo',
            ],
            [
                'addon_board_group_id' => 4,
                'name' => 'Grounds',
                'icon' => 'grass',
            ],
            [
                'addon_board_group_id' => 4,
                'name' => 'Skies',
                'icon' => 'ballon',
            ],
            [
                'addon_board_group_id' => 4,
                'name' => 'Day/Night Cycles',
                'icon' => 'weather_sun',
            ],
        ];

        foreach ($boards as $board) {
            $addonBoard = new AddonBoard();
            $addonBoard->addon_board_group_id = $board['addon_board_group_id'];
            $addonBoard->name = $board['name'];
            $addonBoard->icon = $board['icon'];
            $addonBoard->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_boards');
    }
};
