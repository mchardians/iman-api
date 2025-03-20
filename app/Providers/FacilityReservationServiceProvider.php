<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Services\FacilityReservationService;
use App\Repository\Interfaces\FacilityReservationInterface;

class FacilityReservationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(FacilityReservationInterface::class, FacilityReservationService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
