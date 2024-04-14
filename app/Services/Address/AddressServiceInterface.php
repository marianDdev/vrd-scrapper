<?php

namespace App\Services\Address;

use Symfony\Component\DomCrawler\Crawler;

interface AddressServiceInterface
{
    public function getAddresses(Crawler $crawler): array;
}
