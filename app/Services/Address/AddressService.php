<?php

namespace App\Services\Address;

use Symfony\Component\DomCrawler\Crawler;

class AddressService implements AddressServiceInterface
{
    public function getAddresses(Crawler $crawler): array
    {
        $addresses = [];

        $crawler->filter('h1, h2, h3, h4, h5, h6, p')->each(function (Crawler $node) use (&$addresses) {
            $headingText = strtolower($node->text());
            if (str_contains($headingText, 'address')) {
                $nextElements = $node->nextAll();
                if ($nextElements->count() > 0) {
                    $nextElementText = $nextElements->text();
                    if ($nextElementText && preg_match('/\d{2,}.*\d{5,}/', $nextElementText)) {
                        $addresses[] = trim($nextElementText);
                    }
                }
            }
        });


        return array_unique($addresses);
    }
}
