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
    protected $signature = 'app:scrape-websites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape websites';

    private const WEBSITES_PATH = 'app/public/sample-websites.csv';

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle(): void
    {
        $path = storage_path(self::WEBSITES_PATH);

        $batches = SimpleExcelReader::create($path)->getRows()->chunk(5);

        $jobs = $batches->map(function ($batch) {
            return new ScrapeWebsitesJob($batch);
        })->all();

        Bus::batch($jobs)->name('scrape websites')->dispatch();

        $this->info('Jobs have been dispatched for scraping in chunks.');
    }
}
