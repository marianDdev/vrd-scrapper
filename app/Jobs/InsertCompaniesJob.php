<?php

namespace App\Jobs;

use App\Models\Company;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\LazyCollection;

class InsertCompaniesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly LazyCollection $companies)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->companies as $row) {
            Company::updateOrCreate(
                [
                    'domain'              => $row['domain'],
                    'commercial_name'     => $row['company_commercial_name'],
                    'legal_name'          => $row['company_legal_name'] ?? null,
                    'all_available_names' => $row['company_all_available_names'],
                ]
            );
        }
    }
}
