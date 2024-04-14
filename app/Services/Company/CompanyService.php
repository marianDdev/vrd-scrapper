<?php

namespace App\Services\Company;

use App\Dto\ScrapingResultDto;
use App\Jobs\InsertCompaniesJob;
use App\Models\Company;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
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

    public function update(ScrapingResultDto $dto, string $domain): void
    {
        try {
            $company = Company::where('domain', $domain)->first();
            $company->update(
                [
                    'phone_numbers'      => $dto->phoneNumbers,
                    'social_media_links' => $dto->socialLinks,
                    'address'            => implode(",", $dto->addresses),
                ]
            );

        } catch (Throwable $e) {
            Log::error(sprintf("Error processing website %s", $domain), ['exception' => $e->getMessage()]);
        }
    }
}
