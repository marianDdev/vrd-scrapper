<?php

namespace App\Services;

use App\Helpers\SocialPlatformsExtractor;
use App\Models\SocialMediaLink;

readonly class SocialLinksService implements SocialLinksServiceInterface
{
    public function __construct(private SocialPlatformsExtractor $extractor)
    {
    }

    public function updateOrCreateBatch(int $companyId, array $socialLinks): void
    {
        foreach ($this->extractor->extract($socialLinks, $companyId) as $socialLinkDto) {
            $links[] = [
                'company_id' => $socialLinkDto->companyId,
                'platform'      => $socialLinkDto->platform,
                'url' => $socialLinkDto->url
            ];
            SocialMediaLink::upsert(
                $links,
                uniqueBy: ['company_id', 'platform', 'url'],
                update: ['url'],
            );
        }
    }
}
