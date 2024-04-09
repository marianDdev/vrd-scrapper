<?php

namespace App\Services;

use App\Dto\PhoneNumbersDto;

interface PhoneNumberServiceInterface
{
    public function updateOrCreateBatch(PhoneNumbersDto $dto);
}
