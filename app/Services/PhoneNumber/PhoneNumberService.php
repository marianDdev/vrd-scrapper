<?php

namespace App\Services\PhoneNumber;

use Symfony\Component\DomCrawler\Crawler;

class PhoneNumberService implements PhoneNumberServiceInterface
{
    public function getPhoneNumbers(Crawler $body): array
    {
        $phoneNumbers = [];
        if ($body->count() > 0) {
            $content = $body->text();

            preg_match_all(self::PATTERN, $content, $matches);

            if (!empty($matches[0])) {
                $phoneNumbers = array_unique(array_map(function($number) {
                    return preg_replace('/[^\d\+]/', '', $number);
                }, $matches[0]));
            }
        }

        return array_values($phoneNumbers);
    }
}
