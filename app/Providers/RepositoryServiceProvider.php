<?php

namespace App\Providers;

use App\Repositories\Contracts\FinanceCategoryContract;
use App\Repositories\Contracts\FinanceExpenseContract;
use App\Repositories\Contracts\FinanceIncomeContract;
use App\Repositories\Contracts\FinanceRecapitulationContract;
use App\Repositories\Contracts\InfaqTypeContract;
use App\Repositories\Contracts\NewsCategoryContract;
use App\Repositories\Contracts\RoleContract;
use App\Repositories\Contracts\UserContract;
use App\Repositories\Eloquent\FinanceCategoryRepository;
use App\Repositories\Eloquent\FinanceExpenseRepository;
use App\Repositories\Eloquent\FinanceIncomeRepository;
use App\Repositories\Eloquent\FinanceRecapitulationRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\InfaqTypeRepository;
use App\Repositories\Eloquent\NewsCategoryRepository;
use App\Repositories\Eloquent\RoleRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\Services\ItemService;
use App\Repository\Services\NewsService;
use App\Repository\Services\EventService;
use App\Repository\Interfaces\ItemInterface;
use App\Repository\Interfaces\NewsInterface;
use App\Repository\Interfaces\EventInterface;
use App\Repository\Services\NewsCategoryService;
use App\Repository\Services\EventScheduleService;
use App\Repository\Interfaces\NewsCategoryInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use App\Repository\Interfaces\EventScheduleInterface;
use App\Repository\Services\ExpenseTransactionService;
use App\Repository\Services\FacilityReservationService;
use App\Repository\Services\InventoryTransactionService;
use App\Repository\Interfaces\ExpenseTransactionInterface;
use App\Repository\Services\IncomeInfaqTransactionService;
use App\Repository\Interfaces\FacilityReservationInterface;
use App\Repository\Interfaces\InventoryTransactionInterface;
use App\Repository\Interfaces\IncomeInfaqTransactionInterface;

class RepositoryServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserContract::class, UserRepository::class);
        $this->app->bind(RoleContract::class, RoleRepository::class);
        $this->app->bind(FinanceCategoryContract::class, FinanceCategoryRepository::class);
        $this->app->bind(FinanceIncomeContract::class, FinanceIncomeRepository::class);
        $this->app->bind(FinanceExpenseContract::class, FinanceExpenseRepository::class);
        $this->app->bind(FinanceRecapitulationContract::class, FinanceRecapitulationRepository::class);
        $this->app->bind(NewsCategoryContract::class, NewsCategoryRepository::class);

        $this->app->bind(InfaqTypeContract::class, InfaqTypeRepository::class);
        $this->app->bind(NewsCategoryInterface::class, NewsCategoryService::class);
        $this->app->bind(NewsInterface::class, NewsService::class);
        $this->app->bind(EventInterface::class, EventService::class);
        $this->app->bind(EventScheduleInterface::class, EventScheduleService::class);
        $this->app->bind(IncomeInfaqTransactionInterface::class, IncomeInfaqTransactionService::class);
        $this->app->bind(ExpenseTransactionInterface::class, ExpenseTransactionService::class);
        $this->app->bind(ItemInterface::class, ItemService::class);
        $this->app->bind(InventoryTransactionInterface::class, InventoryTransactionService::class);
        $this->app->bind(FacilityReservationInterface::class, FacilityReservationService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides() {
        return [
            UserContract::class,
            RoleContract::class,
            FinanceCategoryContract::class,
            FinanceIncomeContract::class,
            FinanceExpenseContract::class,
            FinanceRecapitulationContract::class,
            NewsCategoryContract::class,
            InfaqTypeContract::class,
            NewsCategoryInterface::class,
            NewsInterface::class,
            EventInterface::class,
            EventScheduleInterface::class,
            IncomeInfaqTransactionInterface::class,
            ExpenseTransactionInterface::class,
            ItemInterface::class,
            InventoryTransactionInterface::class,
            FacilityReservationInterface::class
        ];
    }
}
