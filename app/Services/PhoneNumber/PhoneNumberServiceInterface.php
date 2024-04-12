<?php

namespace App\Services\PhoneNumber;

use Symfony\Component\DomCrawler\Crawler;

interface PhoneNumberServiceInterface
{
    public function getPhoneNumbers(Crawler $crawler): array;
}
