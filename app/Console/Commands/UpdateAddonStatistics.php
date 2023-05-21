<?php

namespace App\Console\Commands;

use App\Models\Addon;
use Illuminate\Console\Command;

class UpdateAddonStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-addon-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all add-on statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $addons = Addon::all();

        foreach ($addons as $addon) {
            $addon->updateStatistics();
        }
    }
}
