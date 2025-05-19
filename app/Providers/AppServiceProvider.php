<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        DB::listen(function($query) {
            Log::channel("querylog")->debug("SQL: ". $query->sql);
            Log::channel("querylog")->debug("Bindings: ". json_encode($query->bindings));
            Log::channel("querylog")->debug("Time: ". $query->time. "ms");
        });
    }
}
