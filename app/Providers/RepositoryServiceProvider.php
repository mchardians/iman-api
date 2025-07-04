<?php

namespace App\Providers;

use App\Repositories\Contracts\ActivityScheduleContract;
use App\Repositories\Contracts\CommentContract;
use App\Repositories\Contracts\FacilityContract;
use App\Repositories\Contracts\FinanceCategoryContract;
use App\Repositories\Contracts\FinanceExpenseContract;
use App\Repositories\Contracts\FinanceIncomeContract;
use App\Repositories\Contracts\FinanceRecapitulationContract;
use App\Repositories\Contracts\InfaqTypeContract;
use App\Repositories\Contracts\NewsCategoryContract;
use App\Repositories\Contracts\NewsContract;
use App\Repositories\Contracts\RoleContract;
use App\Repositories\Contracts\UserContract;
use App\Repositories\Eloquent\ActivityScheduleRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\FacilityRepository;
use App\Repositories\Eloquent\FinanceCategoryRepository;
use App\Repositories\Eloquent\FinanceExpenseRepository;
use App\Repositories\Eloquent\FinanceIncomeRepository;
use App\Repositories\Eloquent\FinanceRecapitulationRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\NewsCategoryRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\RoleRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

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
        $this->app->bind(NewsContract::class, NewsRepository::class);
        $this->app->bind(CommentContract::class, CommentRepository::class);
        $this->app->bind(FacilityContract::class, FacilityRepository::class);
        $this->app->bind(ActivityScheduleContract::class, ActivityScheduleRepository::class);
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
            NewsContract::class,
            CommentContract::class,
            FacilityContract::class,
            ActivityScheduleContract::class,
            InfaqTypeContract::class,
        ];
    }
}
