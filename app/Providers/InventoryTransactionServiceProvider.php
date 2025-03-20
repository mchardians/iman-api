<?php

namespace App\Providers;

use App\Repository\Interfaces\InventoryTransactionInterface;
use App\Repository\Services\InventoryTransactionService;
use Illuminate\Support\ServiceProvider;

class InventoryTransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(InventoryTransactionInterface::class, InventoryTransactionService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
