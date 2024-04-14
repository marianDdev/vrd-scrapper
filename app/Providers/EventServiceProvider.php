<?php

namespace App\Providers;

use App\Events\WebsiteProcessed;
use App\Listeners\UpdateCompanyListener;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        WebsiteProcessed::class => [
            UpdateCompanyListener::class,
        ]
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
