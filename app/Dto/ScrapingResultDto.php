<?php

namespace App\Dto;

class ScrapingResultDto
{
    public function __construct(
        public array $phoneNumbers,
        public array $socialLinks,
        public array $addresses
    )
    {
    }
}
