<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
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
        Carbon::setLocale('vi');
        if (app()->environment('local') && request()->server('HTTP_HOST') && str_contains(request()->server('HTTP_HOST'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        } else {
        }

        if (app()->environment('local') && request()->server('HTTP_HOST') && str_contains(request()->server('HTTP_HOST'), 'ngrok-free.app')) {
            URL::forceScheme('https');
        } else {
        }
    }
}