<?php

namespace App\Providers;

use App\Services\CompanyService;
use App\Services\CompanyServiceInterface;
use App\Services\HttpBrowserService;
use App\Services\WebScrappingServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CompanyServiceInterface::class, CompanyService::class);
        $this->app->singleton(WebScrappingServiceInterface::class, HttpBrowserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
