<?php

namespace App\Dto;

class PhoneNumbersDto
{
    public function __construct(
        public int $companyId,
        public array $phoneNumbers
    )
    {
    }
}
