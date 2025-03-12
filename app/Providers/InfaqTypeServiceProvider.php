<?php

namespace App\Providers;

use App\Repository\Interfaces\InfaqTypeInterface;
use App\Repository\Services\InfaqTypeService;
use Illuminate\Support\ServiceProvider;

class InfaqTypeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(InfaqTypeInterface::class, InfaqTypeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
