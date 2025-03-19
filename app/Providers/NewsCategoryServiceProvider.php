<?php

namespace App\Providers;

use App\Repository\Interfaces\NewsCategoryInterface;
use App\Repository\Services\NewsCategoryService;
use Illuminate\Support\ServiceProvider;

class NewsCategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(NewsCategoryInterface::class, NewsCategoryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
