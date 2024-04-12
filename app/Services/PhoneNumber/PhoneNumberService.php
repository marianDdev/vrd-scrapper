<?php

namespace App\Services\PhoneNumber;

use Symfony\Component\DomCrawler\Crawler;

class PhoneNumberService implements PhoneNumberServiceInterface
{
    public function getPhoneNumbers(Crawler $crawler): array
    {
        $phoneNumbers = [];
        $body         = $crawler->filter('body');
        if ($body->count() > 0) {
            $content = $body->text(null, true);
            preg_match_all('/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/', $content, $matches);
            if ($matches) {
                $phoneNumbers = $matches[0];
            }
        }

        return $phoneNumbers;
    }
}
