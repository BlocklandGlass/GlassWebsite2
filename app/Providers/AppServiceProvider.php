<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('components/paginator');

        if (file_exists($revPath = base_path('.rev'))) {
            $rev = file_get_contents($revPath);
            $rev = iconv('UTF-8', 'ISO-8859-1//IGNORE', $rev);
            view()->share('revision', $rev);

            $revTime = date('Y-m-d', filemtime($revPath));
            view()->share('revisionTime', $revTime);
        }
    }
}
