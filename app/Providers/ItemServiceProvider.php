<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Services\ItemService;
use App\Repository\Interfaces\ItemInterface;

class ItemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ItemInterface::class, ItemService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
