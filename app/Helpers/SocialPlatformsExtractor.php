<?php

namespace App\Helpers;

use App\Dto\SocialLinkDto;

class SocialPlatformsExtractor
{
    private const PLATFORMS = [
        'facebook.com' => 'Facebook',
        'twitter.com' => 'Twitter',
        'instagram.com' => 'Instagram',
        'linkedin.com' => 'LinkedIn',
    ];

    /**
     * @return SocialLinkDto[]
     */
    public function extract(array $links, int $companyId): array
    {
        $socialLinksDtos = [];

        foreach ($links as $link) {
            $host = parse_url($link, PHP_URL_HOST) ?: '';

            foreach (self::PLATFORMS as $domain => $name) {
                if (str_contains($host, $domain)) {
                    $socialLinksDtos[] = new SocialLinkDto($companyId, $name, $this->ensureScheme($link));
                    break;
                }
            }
        }

        return $socialLinksDtos;
    }

    private function ensureScheme(string $url): string
    {
        return parse_url($url, PHP_URL_SCHEME) === null ? 'https:' . $url : $url;
    }
}
