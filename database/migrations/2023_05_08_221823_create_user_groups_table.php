<?php

use App\Models\UserGroup;
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
        Schema::create('user_groups', function (Blueprint $table) {
            $table->id();
            $table->tinyText('name')->unique();
            $table->tinyText('description');
            $table->tinyText('color');
            $table->tinyText('icon');
            $table->timestamps();
        });

        $groups = [
            [
                'name' => 'Administrator',
                'description' => 'Granted all abilities.',
                'color' => 'e74c3c',
                'icon' => 'key',
            ],
            [
                'name' => 'Mod Reviewer',
                'description' => 'Granted abilities to approve and reject add-ons.',
                'color' => '7de260',
                'icon' => 'document_mark_as_final',
            ],
        ];

        foreach ($groups as $group) {
            $userGroup = new UserGroup();
            $userGroup->name = $group['name'];
            $userGroup->description = $group['description'];
            $userGroup->color = $group['color'];
            $userGroup->icon = $group['icon'];
            $userGroup->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_groups');
    }
};
