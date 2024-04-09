<?php

namespace App\Services;

use App\Dto\PhoneNumbersDto;
use App\Dto\ScrapingResultDto;
use App\Models\Company;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;
use Exception;
use Throwable;

readonly class HttpBrowserService implements WebScrappingServiceInterface
{
    public function __construct(
        private LoggerInterface             $logger,
        private PhoneNumberServiceInterface $phoneNumberService,
        private SocialLinksServiceInterface $socialLinksService,
        private CompanyServiceInterface     $companyService
    )
    {
    }

    public function processWebsites(LazyCollection $batch): void
    {
        $browser = $this->buildHttpBrowser();

        foreach ($batch as $website) {
            Log::info("Starting processing website: " . $website['domain']);
            $crawler = $this->getCrawler($browser, $website['domain']);

            if (!$crawler) {
                continue;
            }

            $this->updateContactDetails($crawler, $website['domain']);
        }
    }

    private function updateContactDetails(Crawler $crawler, string $domain): void
    {
        try {
            $resultDto = $this->getScrapingResult($crawler);
            $company   = Company::where('domain', $domain)->first();

            $this->phoneNumberService->updateOrCreateBatch(new PhoneNumbersDto($company->id, $resultDto->phoneNumbers));
            $this->socialLinksService->updateOrCreateBatch($company->id, $resultDto->socialLinks);
            $this->companyService->updateAddresses($company, $resultDto->addresses);

        } catch (Throwable $e) {
            Log::error(sprintf("Error processing website %s", $domain), ['exception' => $e->getMessage()]);
        }
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
            $phoneNumbers = $this->extractPhoneNumbers($crawler);
            $socialLinks  = $this->extractSocialLinks($crawler);
            $addresses    = $this->extractAddresses($crawler);
        } catch (Exception $e) {
            $this->logger->error(sprintf('Error during scraping: %s', $e->getMessage()));

            throw $e;
        }

        return new ScrapingResultDto($phoneNumbers, $socialLinks, $addresses);
    }

    private function buildHttpBrowser(): HttpBrowser
    {
        return new HttpBrowser(
            HttpClient::create(
                [
                    'verify_peer'  => false,
                    'verify_host'  => false,
                    'timeout'      => 30,
                    'max_duration' => 10,
                ]
            )
        );
    }

    private function extractPhoneNumbers(Crawler $crawler): array
    {
        $phoneNumbers = [];
        $body         = $crawler->filter('body');
        if ($body->count() > 0) {
            $content = $body->text(null, true); // Pass true to keep white spaces as they are.
            preg_match_all('/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/', $content, $matches);
            if ($matches) {
                $phoneNumbers = $matches[0];
            }
        }

        return $phoneNumbers;
    }

    private function extractSocialLinks(Crawler $crawler): array
    {
        $links       = $crawler->filter('a');
        $socialLinks = [];

        if ($links->count() > 0) {
            $socialLinks = $links->each(function ($node) {
                $href = $node->attr('href');
                if (str_contains($href, 'facebook.com') ||
                    str_contains($href, 'twitter.com') ||
                    str_contains($href, 'instagram.com') ||
                    str_contains($href, 'linkedin.com')) {
                    return $href;
                }
            });
        }

        return array_values(array_filter($socialLinks));
    }


    private function extractAddresses(Crawler $crawler): array
    {
        $addresses = [];

        $crawler->filter('h1, h2, h3, h4, h5, h6')->each(function (Crawler $node) use (&$addresses) {
            $headingText = strtolower($node->text());
            if (str_contains($headingText, 'address')) {
                $nextElementText = $node->nextAll()->text();
                if ($nextElementText && preg_match('/\d{2,}.*\d{5,}/', $nextElementText)) {
                    $addresses[] = trim($nextElementText);
                }
            }
        });

        return array_unique($addresses);
    }
}
