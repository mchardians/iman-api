<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Services\ItemService;
use App\Repository\Services\NewsService;
use App\Repository\Services\RoleService;
use App\Repository\Services\EventService;
use App\Repositories\Contracts\UserContract;
use App\Repository\Interfaces\ItemInterface;
use App\Repository\Interfaces\NewsInterface;
use App\Repository\Interfaces\RoleInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repository\Interfaces\EventInterface;
use App\Repository\Services\InfaqTypeService;
use App\Repository\Services\NewsCategoryService;
use App\Repository\Interfaces\InfaqTypeInterface;
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
        $this->app->bind(RoleInterface::class, RoleService::class);
        $this->app->bind(NewsCategoryInterface::class, NewsCategoryService::class);
        $this->app->bind(NewsInterface::class, NewsService::class);
        $this->app->bind(EventInterface::class, EventService::class);
        $this->app->bind(EventScheduleInterface::class, EventScheduleService::class);
        $this->app->bind(InfaqTypeInterface::class, InfaqTypeService::class);
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
            RoleInterface::class,
            NewsCategoryInterface::class,
            NewsInterface::class,
            EventInterface::class,
            EventScheduleInterface::class,
            InfaqTypeInterface::class,
            IncomeInfaqTransactionInterface::class,
            ExpenseTransactionInterface::class,
            ItemInterface::class,
            InventoryTransactionInterface::class,
            FacilityReservationInterface::class
        ];
    }
}
