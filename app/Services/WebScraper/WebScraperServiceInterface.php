<?php

namespace App\Services\WebScraper;

interface WebScraperServiceInterface
{
    const VERIFY_PEER  = false;
    const VERIFY_HOST  = false;
    const TIMEOUT      = 30;
    const MAX_DURATION = 10;

    public function processWebsite(array $website): void;
}
