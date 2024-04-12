<?php

namespace App\Services\Link;

use Symfony\Component\DomCrawler\Crawler;

interface LinksServiceInterface
{
    const PLATFORMS = [
        'twitter',
        'instagram',
        'linkedin',
        'youtube',
        'pinterest',
        'snapchat',
        'tiktok',
        'reddit',
        'tumblr',
        'flickr',
        'telegram',
        'whatsapp',
        'vimeo',
        'medium',
        'quora',
        'discord',
    ];

    public function getSocialMediaLinks(Crawler $crawler): array;
}
