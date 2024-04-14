<?php

namespace App\Services\PhoneNumber;

use Symfony\Component\DomCrawler\Crawler;

interface PhoneNumberServiceInterface
{
    const PATTERN = '/\+?(\d{1,3})[ .-]?(\(\d{2,3}\)|\d{2,3})[ .-]?(\d{3})[ .-]?(\d{4})/';

    public function getPhoneNumbers(Crawler $crawler): array;
}
