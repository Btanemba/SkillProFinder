<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        // Prevent lazy loading in production to catch N+1 queries
        Model::preventLazyLoading(! app()->isProduction());
        
        // Disable strict mode checks in production for better performance
        Model::shouldBeStrict(! app()->isProduction());
        
        // Only log slow queries (taking more than 1000ms)
        DB::whenQueryingForLongerThan(1000, function ($connection, $event) {
            logger()->warning('Slow query detected', [
                'sql' => $event->sql,
                'time' => $event->time,
            ]);
        });
    }
}
