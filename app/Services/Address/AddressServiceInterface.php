<?php

namespace App\Services\Address;

use Symfony\Component\DomCrawler\Crawler;

interface AddressServiceInterface
{
    const ADDRESS_KEYWORDS = [
        'address',
        'city',
        'county',
        'state',
        'street',
        'avenue',
        'boulevard',
        'postal code',
        'municipality',
        'zip code',
        'road',
        'lane',
        'province',
        'region',
        'district',
    ];

    public function getAddresses(Crawler $body): array;
}
