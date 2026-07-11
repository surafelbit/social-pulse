<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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
     *
     * Forces HTTPS for all generated URLs when the app is deployed behind
     * Render's HTTPS load balancer (which proxies HTTP to the container).
     */
    public function boot(): void
    {
        // Force HTTPS scheme in production so Vite/Laravel asset URLs
        // are generated with https:// instead of http://, preventing
        // Mixed Content errors when served behind a reverse proxy.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Vite::prefetch(concurrency: 3);
    }
}
