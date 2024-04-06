<?php

namespace App\Helpers;

class SocialPlatformsExtractor
{
    public function extract(array $links): array
    {
        $platforms = [];

        foreach ($links as $link) {
            $scheme = parse_url($link, PHP_URL_SCHEME);
            if (empty($scheme)) {
                $link = 'https:' . $link;
            }

            $host      = parse_url($link, PHP_URL_HOST);
            $hostParts = explode('.', $host);

            if (count($hostParts) >= 2) {
                $domain = $hostParts[count($hostParts) - 2];
            } else {
                $domain = $hostParts[0];
            }

            $platform = match ($domain) {
                'facebook' => 'Facebook',
                'twitter' => 'Twitter',
                'instagram' => 'Instagram',
                'linkedin' => 'LinkedIn',
                default => ucfirst($domain),
            };

            if (!array_key_exists($platform, $platforms)) {
                $platforms[$platform] = [
                    'platform' => $platform,
                    'url'      => $link,
                ];
            }
        }

        return array_values($platforms);
    }
}
