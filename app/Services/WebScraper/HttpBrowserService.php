<?php

namespace App\Services\WebScraper;

use App\Dto\ScrapingResultDto;
use App\Events\WebsiteProcessed;
use App\Services\Address\AddressServiceInterface;
use App\Services\Company\CompanyServiceInterface;
use App\Services\Link\LinksServiceInterface;
use App\Services\PhoneNumber\PhoneNumberServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
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
        $browser = $this->buildHttpBrowser();

        Log::info("Starting processing website: " . $website['domain']);
        $crawler = $this->getCrawler($browser, $website['domain']);

        if (!$crawler) {
            return;
        }

        event(new WebsiteProcessed($this->getScrapingResult($crawler), $website['domain']));
    }

    private function buildHttpBrowser(): HttpBrowser
    {
        return new HttpBrowser(HttpClient::create(
            [
                'verify_peer'  => self::VERIFY_PEER,
                'verify_host'  => self::VERIFY_HOST,
                'timeout'      => self::TIMEOUT,
                'max_duration' => self::MAX_DURATION,
            ]
        )
        );
    }

    private function getCrawler(HttpBrowser $browser, string $domain): ?Crawler
    {
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
    private function getScrapingResult(Crawler $crawler): ScrapingResultDto
    {
        try {
            $phoneNumbers = $this->phoneNumberService->getPhoneNumbers($crawler);
            $socialLinks  = $this->socialLinksService->getSocialMediaLinks($crawler);
            $addresses    = $this->addressService->getAddresses($crawler);
        } catch (Exception $e) {
            $this->logger->error(sprintf('Error during scraping: %s', $e->getMessage()));

            throw $e;
        }

        return new ScrapingResultDto($phoneNumbers, $socialLinks, $addresses);
    }
}
