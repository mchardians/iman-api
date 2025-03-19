<?php

namespace App\Providers;

use App\Repository\Interfaces\ExpenseTransactionInterface;
use App\Repository\Services\ExpenseTransactionService;
use Illuminate\Support\ServiceProvider;

class ExpenseTransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ExpenseTransactionInterface::class, ExpenseTransactionService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
