<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\ServiceProvider;
use App\Repository\Services\EventService;
use App\Repository\Interfaces\EventInterface;

class EventsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this-> app->bind(EventInterface::class, EventService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
