<?php

namespace App\Services\Address;

use Symfony\Component\DomCrawler\Crawler;

class AddressService implements AddressServiceInterface
{
    public function getAddresses(Crawler $body): array
    {
        $addresses = [];

        $body->filter('h1, h2, h3, h4, h5, h6, p')->each(function (Crawler $node) use (&$addresses) {
            $text = strtolower($node->text());
            if ($this->isAddress($text)) {
                $nextElements = $node->nextAll();
                if ($nextElements->count() > 0) {
                    $nextElementText = $nextElements->text();
                    if ($nextElementText && preg_match('/\d{1,5}\s\w+\s(\w+\s?){1,3},\s[A-Z]{2}\s\d{5}/', $nextElementText)) {
                        $addresses[] = trim($nextElementText);
                    }
                }
            }
        });

        return array_unique($addresses);
    }

    private function isAddress(string $text): bool
    {
        return collect(self::ADDRESS_KEYWORDS)->contains(function ($keyword) use ($text) {
            return str_contains($text, $keyword);
        });
    }
}
