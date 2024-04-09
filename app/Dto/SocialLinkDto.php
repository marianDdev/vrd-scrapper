<?php

namespace App\Dto;

class SocialLinkDto
{
    public function __construct(
        public int $companyId,
        public string $platform,
        public string $url
    )
    {
    }
}
