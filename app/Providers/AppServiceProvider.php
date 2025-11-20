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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production (Railway)
        // Check multiple conditions to ensure HTTPS is forced
        $forceHttps = false;
        
        // Check if environment is production
        if ($this->app->environment('production')) {
            $forceHttps = true;
        }
        
        // Check if request is already secure
        if (request()->secure()) {
            $forceHttps = true;
        }
        
        // Check Railway's X-Forwarded-Proto header
        if (request()->header('X-Forwarded-Proto') === 'https') {
            $forceHttps = true;
        }
        
        // Check if APP_URL contains https
        $appUrl = config('app.url', '');
        if (str_starts_with($appUrl, 'https://')) {
            $forceHttps = true;
        }
        
        if ($forceHttps) {
            URL::forceScheme('https');
        }
    }
}
