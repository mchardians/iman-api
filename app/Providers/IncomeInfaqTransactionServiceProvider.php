<?php

namespace App\Providers;

use App\Repository\Interfaces\IncomeInfaqTransactionInterface;
use App\Repository\Services\IncomeInfaqTransactionService;
use Illuminate\Support\ServiceProvider;

class IncomeInfaqTransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IncomeInfaqTransactionInterface::class, IncomeInfaqTransactionService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
