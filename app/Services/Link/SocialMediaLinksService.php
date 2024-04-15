<?php

namespace App\Services\Link;

use Symfony\Component\DomCrawler\Crawler;

readonly class SocialMediaLinksService implements LinksServiceInterface
{
    public function getSocialMediaLinks(Crawler $body): array
    {
        $links = $body->filter('a');
        if ($links->count() === 0) {
            return [];
        }

        $socialLinks = [];
        $links->each(function ($node) use (&$socialLinks) {
            $href = $node->attr('href');
            if (!$href) {
                return;
            }

            $domain = $this->extractDomain($href);
            if (in_array($domain, self::PLATFORMS)) {
                $socialLinks[$domain] = [
                    'platform' => $domain,
                    'url'      => $href,
                ];
            }
        });

        return array_values($socialLinks);
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
