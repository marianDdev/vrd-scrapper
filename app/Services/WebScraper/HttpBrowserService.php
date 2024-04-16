<?php

namespace App\Services\WebScraper;

use App\Dto\ScrapingResultDto;
use App\Events\WebsiteProcessed;
use App\Factories\HttpBrowserFactory;
use App\Services\Address\AddressServiceInterface;
use App\Services\Link\LinksServiceInterface;
use App\Services\PhoneNumber\PhoneNumberServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class HttpBrowserService implements WebScraperServiceInterface
{
    public function __construct(
        private LinksServiceInterface       $socialLinksService,
        private PhoneNumberServiceInterface $phoneNumberService,
        private AddressServiceInterface     $addressService,
        private LoggerInterface             $logger,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function processWebsite(array $website): void
    {
        Log::info(sprintf("Starting processing website: %s", $website['domain']));
        $result = $this->getScrapingResult($website['domain']);

        if (!$result) {
            return;
        }

        event(new WebsiteProcessed($this->getResultDto($result), $website['domain']));
    }

    private function getScrapingResult(string $domain): ?Crawler
    {
        $browser = HttpBrowserFactory::createHttpBrowser();
        try {
            return $browser->request('GET', sprintf('https://%s', $domain));
        } catch (TransportExceptionInterface $e) {
            Log::error(sprintf("HTTPS request failed for %s", $domain), ['exception' => $e->getMessage()]);
            try {
                return $browser->request('GET', sprintf('http://%s', $domain));
            } catch (TransportExceptionInterface $e) {
                Log::error(sprintf("HTTP request also failed for %s", $domain), ['exception' => $e->getMessage()]);

                return null;
            }
        } catch (Exception $e) {
            Log::error(sprintf("An unexpected error occurred for %s", $domain), ['exception' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * @throws Exception
     */
    private function getResultDto(Crawler $result): ScrapingResultDto
    {
        try {
            $body = $result->filter('body');
            $phoneNumbers = $this->phoneNumberService->getPhoneNumbers($body);
            $socialLinks  = $this->socialLinksService->getSocialMediaLinks($body);
            $addresses    = $this->addressService->getAddresses($body);
        } catch (Exception $e) {
            $this->logger->error(sprintf('Error during scraping: %s', $e->getMessage()));

            throw $e;
        }

        return new ScrapingResultDto($phoneNumbers, $socialLinks, $addresses);
    }
}
