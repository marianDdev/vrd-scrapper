<?php

namespace App\Providers;

use App\Services\Address\AddressService;
use App\Services\Address\AddressServiceInterface;
use App\Services\Company\CompanyService;
use App\Services\Company\CompanyServiceInterface;
use App\Services\File\CsvService;
use App\Services\File\FileServiceInterface;
use App\Services\Link\LinksServiceInterface;
use App\Services\Link\SocialMediaLinksService;
use App\Services\PhoneNumber\PhoneNumberService;
use App\Services\PhoneNumber\PhoneNumberServiceInterface;
use App\Services\Search\SearchService;
use App\Services\Search\SearchServiceInterface;
use App\Services\Statistics\StatisticsService;
use App\Services\Statistics\StatisticsServiceInterface;
use App\Services\WebScraper\HttpBrowserService;
use App\Services\WebScraper\WebScraperServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CompanyServiceInterface::class, CompanyService::class);
        $this->app->singleton(WebScraperServiceInterface::class, HttpBrowserService::class);
        $this->app->singleton(PhoneNumberServiceInterface::class, PhoneNumberService::class);
        $this->app->singleton(LinksServiceInterface::class, SocialMediaLinksService::class);
        $this->app->singleton(AddressServiceInterface::class, AddressService::class);
        $this->app->singleton(FileServiceInterface::class, CsvService::class);
        $this->app->singleton(StatisticsServiceInterface::class, StatisticsService::class);
        $this->app->singleton(SearchServiceInterface::class, SearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
