<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

class HttpBrowserService implements WebScrappingServiceInterface
{
    public function getScrapingResult(Crawler $crawler): array
    {
        $phoneNumbers = [];
        $socialLinks = [];
        $addresses = [];
        $body = $crawler->filter('body');

        if ($body->count() > 0) {
            $content = $body->text();
            preg_match_all('/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/', $content, $matches);
            if ($matches) {
                $phoneNumbers = $matches[0];
            }
        }

        $links = $crawler->filter('a');

        if ($links->count() > 0) {
            $socialLinks = $crawler->filter('a')->each(function ($node) {
                $href = $node->attr('href');
                if (str_contains($href, 'facebook.com') || str_contains($href, 'twitter.com') || str_contains($href, 'instagram.com') || str_contains($href, 'linkedin')) {
                    return $href;
                }
            });
            $socialLinks = array_filter($socialLinks);
        }

        $address = $crawler->filter('p.address');

        if ($address->count() > 0) {
            $addresses = $crawler->filter('p.address')->each(function ($node) {
                return trim($node->text());
            });
        }


        return [
            'phone_numbers' => $phoneNumbers,
            'social_links' => array_values($socialLinks),
            'addresses' => $addresses,
        ];
    }
}
