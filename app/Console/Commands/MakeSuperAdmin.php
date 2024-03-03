<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class MakeSuperAdmin extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-super-admin {steamId : The 64-bit SteamID of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user Super Admin by SteamID64';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('steam_id', $this->argument('steamId'))->first();

        if ($user === null) {
            $this->error('The user does not exist.');

            return;
        }

        $user->assignRole('Super Admin');

        $this->info($user->name.' has become Super Admin (Auto)');
    }
}
