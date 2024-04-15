<?php

namespace App\Services\Search;

use App\Models\Company;
use Illuminate\Support\Collection;

class SearchService implements SearchServiceInterface
{
    public function search(array $params): Collection
    {
        $keyword      = collect($params)->first();
        $builder      = Company::search($keyword);
        $facetFilters = [];
        $filters      = [];

        if (isset($params['name']) && $params['name'] !== $keyword) {
            $filters[] = 'name:' . $params['name'];
        }

        if (isset($params['phone_number']) && $params['phone_number'] !== $keyword) {
            $facetFilters[] = 'phone_numbers:' . preg_replace('/[^\d\+]/', '', $params['phone_number']);
        }

        if (isset($params['website'])) {
            $website = $this->cleanUrl($params['website']);
            if ($this->cleanUrl($params['website'])) {
                $facetFilters[] = 'domain:' . $website;
            }
        }

        if (isset($params['facebook']) && $params['facebook'] !== $keyword) {
            $facetFilters[] = 'social_media_links.url:' . $params['facebook'];
        }

        $filterString = implode(' AND ', $filters);

        return $builder->with(['filters' => $filterString, 'facetFilters' => $facetFilters])->get();
    }

    private function cleanUrl($url): ?string
    {
        if (preg_match(self::URL_PATTERN, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
