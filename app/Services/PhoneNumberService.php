<?php

namespace App\Services;

use App\Dto\PhoneNumbersDto;
use App\Models\PhoneNumber;

class PhoneNumberService implements PhoneNumberServiceInterface
{
    public function updateOrCreateBatch(PhoneNumbersDto $dto): void
    {
        $phoneNumbers = [];
        foreach ($dto->phoneNumbers as $phoneNumber) {
            $phoneNumbers[] = [
                'company_id' => $dto->companyId,
                'phone'      => $phoneNumber,
            ];
        }
        PhoneNumber::upsert(
            $phoneNumbers,
            uniqueBy: ['phone', 'company_id'],
            update: ['phone']
        );
    }
}
