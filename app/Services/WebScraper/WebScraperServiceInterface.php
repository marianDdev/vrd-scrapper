<?php

namespace App\Services\WebScraper;

interface WebScraperServiceInterface
{
    public function processWebsite(array $website): void;
}
