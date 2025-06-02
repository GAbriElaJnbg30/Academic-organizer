<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        URL::forceScheme('https');
        ini_set('upload_max_filesize', '100M');
        ini_set('post_max_size', '100M');
    }

    /**
     * Bootstrap any application services.
     */
   
}
