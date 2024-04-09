<?php

namespace App\Services;

use Illuminate\Support\LazyCollection;

interface WebScrappingServiceInterface
{
    public function processWebsites(LazyCollection $batch): void;
}
