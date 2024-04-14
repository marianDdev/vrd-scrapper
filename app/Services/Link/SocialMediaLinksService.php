<?php

namespace App\Services\Link;

use Symfony\Component\DomCrawler\Crawler;

readonly class SocialMediaLinksService implements LinksServiceInterface
{
    public function getSocialMediaLinks(Crawler $crawler): array
    {
        $links = $crawler->filter('a');
        if ($links->count() === 0) {
            return [];
        }

        $socialLinks = $links->each(function ($node) {
            $href   = $node->attr('href');
            if (!$href) {
                return [];
            }

            $domain = $this->extractDomain($href);

            if (in_array($domain, self::PLATFORMS)) {
                return [
                    'platform' => $domain,
                    'url' => $href
                ];
            }
        });

        return array_values(array_filter($socialLinks));
    }

    private function extractDomain(string $href): string
    {
        $host = parse_url($href, PHP_URL_HOST);
        if (!$host) {
            return '';
        }

        $hostParts = explode('.', $host);

        return count($hostParts) > 2 ? $hostParts[count($hostParts) - 2] : $hostParts[0];
    }
}
