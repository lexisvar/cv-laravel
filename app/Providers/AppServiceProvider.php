<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Behind Railway's proxy, generate https:// URLs (assets, og:image, links).
        if (config('app.force_https')) {
            // Always link to the main domain (APP_URL), even when the site is
            // reached through another address such as *.up.railway.app.
            if (str_starts_with((string) config('app.url'), 'https://')) {
                URL::forceRootUrl(config('app.url'));
            }
            URL::forceScheme('https');
        }
    }
}
