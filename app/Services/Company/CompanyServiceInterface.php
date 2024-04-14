<?php

namespace App\Services\Company;

use App\Dto\ScrapingResultDto;

interface CompanyServiceInterface
{
    public function createFromCsv(string $path): void;

    public function update(ScrapingResultDto $dto, string $domain): void;

}
