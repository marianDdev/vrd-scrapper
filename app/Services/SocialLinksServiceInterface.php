<?php

namespace App\Services;

interface SocialLinksServiceInterface
{
    public function updateOrCreateBatch(int $companyId, array $socialLinks): void;
}
