<?php

namespace App\Jobs;

use App\Helpers\SocialPlatformsExtractor;
use App\Models\Company;
use App\Models\PhoneNumber;
use App\Models\SocialMediaLink;
use App\Services\WebScrappingServiceInterface;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\LazyCollection;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ScrapeWebsitesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly LazyCollection $chunk)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(WebScrappingServiceInterface $webScrappingService, SocialPlatformsExtractor $socialPlatformsExtractor): void
    {
        $browser = new HttpBrowser(HttpClient::create());
        foreach ($this->chunk as $website) {

            try {
                $crawler = $browser->request('GET', sprintf('https://%s', $website['domain']));
            } catch (TransportExceptionInterface $e) {
                try {
                    $crawler = $browser->request('GET', sprintf('http://%s', $website['domain']));
                } catch (TransportExceptionInterface $e) {
                    continue;
                }

            }
            $result = $webScrappingService->getScrapingResult($crawler);

            $company = Company::where('domain', $website['domain'])->first();

            foreach ($result['phone_numbers'] as $phoneNumber) {
                PhoneNumber::updateOrcreate(
                    [
                        'company_id' => $company->id,
                        'phone'      => $phoneNumber,
                    ]
                );
            }

            $socialPlatforms = $socialPlatformsExtractor->extract($result['social_links']);
            foreach ($socialPlatforms as $socialPlatform) {
                SocialMediaLink::updateOrcreate([
                                                    'company_id' => $company->id,
                                                    'platform'   => $socialPlatform['platform'],
                                                    'url'        => $socialPlatform['url'],
                                                ]);
            }
        }
    }
}
