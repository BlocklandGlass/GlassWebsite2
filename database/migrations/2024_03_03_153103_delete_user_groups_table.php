<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('user_groups');
        DB::delete('DELETE FROM migrations WHERE migration = "2023_05_08_221823_create_user_groups_table"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
