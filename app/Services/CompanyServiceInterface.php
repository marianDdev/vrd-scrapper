<?php

namespace App\Services;

interface CompanyServiceInterface
{
    public function createFromCsv(string $path): void;
}
