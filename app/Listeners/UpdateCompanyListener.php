<?php

namespace App\Listeners;

use App\Events\WebsiteProcessed;
use App\Services\Company\CompanyServiceInterface;

class UpdateCompanyListener
{
    /**
     * Create the event listener.
     */
    public function __construct(private CompanyServiceInterface $companyService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(WebsiteProcessed $event): void
    {
        $this->companyService->update($event->dto, $event->domain);
    }
}
