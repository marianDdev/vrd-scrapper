<?php

namespace App\Services;

use App\Models\Company;

interface CompanyServiceInterface
{
    public function createFromCsv(string $path): void;

    public function updateAddresses(Company $company, array $addresses = []): void;
}
