<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadWebsitesRequest;
use App\Jobs\ScrapeWebsitesJob;
use App\Services\File\FileServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Throwable;

class WebScrapingController extends Controller
{
    /**
     * @throws Throwable
     */
    public function startScraping(
        UploadWebsitesRequest $request,
        FileServiceInterface  $fileService
    ): JsonResponse
    {
        try {
            $file = $request->file('file');
            $rows = $fileService->getRows($file);

            $jobs = [];
            $rows->each(function ($row) use (&$jobs) {
                if (isset($row['domain']) && $this->isValidDomain($row['domain'])) {
                    $jobs[] = new ScrapeWebsitesJob($row);
                }
            });

            Storage::delete($file->path());

            Bus::batch($jobs)->name('scrape websites')->dispatch();

            return new JsonResponse(['message' => 'Scraping process started, jobs dispatched.']);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'message' => sprintf(
                        'Something went wrong: %s. Trace: %s',
                        $e->getMessage(),
                        $e->getTraceAsString()
                    ),
                ],
                500
            );
        }
    }

    private function isValidDomain($domain)
    {
        return filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }
}
