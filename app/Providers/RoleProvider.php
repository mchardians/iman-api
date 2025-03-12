<?php

namespace App\Providers;

use App\Repository\Interfaces\RoleInterface;
use App\Repository\Services\RoleService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class RoleProvider extends ServiceProvider implements DeferrableProvider
{
    // public array $singletons = [
    //     RoleService::class => RoleInterface::class,
    // ];

    // public function provides(): array
    // {
    //     return [
    //         RoleService::class,
    //     ];
    // }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RoleInterface::class, RoleService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
