<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Schema::defaultStringLength(191);

        // Normalize application URL host/protocol to avoid session/redirect mismatch
        // Ensures redirects use the same host as requests (e.g., 127.0.0.1 vs localhost)
        $appUrl = config('app.url');
        if (!empty($appUrl)) {
            try {
                URL::forceRootUrl($appUrl);
                if (str_starts_with($appUrl, 'https://')) {
                    URL::forceScheme('https');
                } else {
                    URL::forceScheme('http');
                }
            } catch (\Throwable $e) {
                // Silent: do not break app if APP_URL is malformed
            }
        }

        // Enable query logging in development
        if (config('app.debug') && config('app.env') !== 'production') {
            DB::listen(function ($query) {
                if ($query->time > 100) { // Log queries taking more than 100ms
                    Log::warning('Slow Query Detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms',
                    ]);
                }
            });
        }
    }
}
