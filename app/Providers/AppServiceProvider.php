<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('panel-login', function (Request $request) {
            $maxAttempts = max(3, (int) config('wa_gateway.panel_login_rate_limit', 10));
            $username = (string) $request->input('username', '');

            return [
                Limit::perMinute($maxAttempts)->by($request->ip() . '|' . $username),
            ];
        });
    }
}
