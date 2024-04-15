<?php

namespace App\Services\Search;

use Illuminate\Support\Collection;

interface SearchServiceInterface
{
    public const URL_PATTERN = '/(?:www\.)?(\b[\w-]+\.[\w-]+\b)/';

    public function search(array $params): Collection;
}
