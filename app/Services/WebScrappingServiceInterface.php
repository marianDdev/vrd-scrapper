<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;

interface WebScrappingServiceInterface
{
    public function getScrapingResult(Crawler $crawler): array;
}
