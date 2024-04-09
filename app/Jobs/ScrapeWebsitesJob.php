<?php

namespace App\Jobs;

use App\Services\WebScrappingServiceInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\LazyCollection;

class ScrapeWebsitesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly LazyCollection $batch)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(WebScrappingServiceInterface $webScrappingService): void
    {
        $webScrappingService->processWebsites($this->batch);
    }
}
