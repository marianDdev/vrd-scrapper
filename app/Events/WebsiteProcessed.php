<?php

namespace App\Events;

use App\Dto\ScrapingResultDto;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebsiteProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public readonly ScrapingResultDto $dto,
        public readonly string $domain
    )
    {
    }
}
