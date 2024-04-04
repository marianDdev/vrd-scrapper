<?php

namespace App\Services;

use App\Jobs\InsertCompaniesJob;
use Illuminate\Support\Facades\Bus;
use Spatie\SimpleExcel\SimpleExcelReader;
use Throwable;

class CompanyService implements CompanyServiceInterface
{
    /**
     * @throws Throwable
     */
    public function createFromCsv(string $path): void
    {
        $batch = Bus::batch([])
                    ->name('Insert companies from csv file')
                    ->dispatch();

        $chunks = SimpleExcelReader::create($path)
                                   ->getRows()
                                   ->chunk(100);

        foreach ($chunks as $chunk) {
            $batch->add(new InsertCompaniesJob($chunk));
        }
    }
}
