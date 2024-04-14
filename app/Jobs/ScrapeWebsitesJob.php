<?php

namespace App\Jobs;

use App\Services\WebScraper\WebScraperServiceInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScrapeWebsitesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly array $website)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(WebScraperServiceInterface $webScrappingService): void
    {
        $webScrappingService->processWebsite($this->website);
    }
}
