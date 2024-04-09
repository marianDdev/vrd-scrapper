<?php

namespace App\Providers;

use App\Services\CompanyService;
use App\Services\CompanyServiceInterface;
use App\Services\HttpBrowserService;
use App\Services\PhoneNumberService;
use App\Services\PhoneNumberServiceInterface;
use App\Services\SocialLinksService;
use App\Services\SocialLinksServiceInterface;
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
        $this->app->singleton(PhoneNumberServiceInterface::class, PhoneNumberService::class);
        $this->app->singleton(SocialLinksServiceInterface::class, SocialLinksService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
