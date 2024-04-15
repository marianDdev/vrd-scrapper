<?php

namespace Database\Seeders;

use App\Services\Company\CompanyServiceInterface;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CompanySeeder extends Seeder
{
    private const CSV_PATH = 'sample-websites-company-names.csv';

    public function __construct(private readonly CompanyServiceInterface $companyService)
    {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $path = database_path(self::CSV_PATH);
            $this->companyService->createFromCsv($path);
        } catch (Exception $e) {
            $this->command->error(sprintf("An error occurred: %s", $e->getMessage()));
            Log::error('Error importing companies from CSV.', ['exception' => $e->getMessage()]);
        }

        $this->command->info('Companies imported successfully.');
    }
}
