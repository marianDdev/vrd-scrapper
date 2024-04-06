<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeWebsitesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Spatie\SimpleExcel\SimpleExcelReader;
use Throwable;

class ScrapeWebsitesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-websites-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape websites';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        $path = storage_path('app/public/sample-websites.csv');

        $chunks = SimpleExcelReader::create($path)
                                   ->getRows()
                                   ->chunk(10);

        $jobs = $chunks->map(function ($chunk) {
            return new ScrapeWebsitesJob($chunk);
        })->all();

        Bus::batch($jobs)
           ->name('scrape websites')
           ->dispatch();

        $this->info('Jobs have been dispatched for scraping in chunks.');
    }
}
