<?php

namespace App\Services\Search;

use App\Models\Company;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchService implements SearchServiceInterface
{
    public function search(array $params): Collection|LengthAwarePaginator
    {
        if (!isset($params['keyword'])) {
            return Company::paginate(50);
        }

        $keyword      = $params['keyword'];




        $builder      = Company::search($keyword);
        $facetFilters = [];
        $filters      = [];

        if (isset($params['name'])) {
            $filters[] = 'name:' . $params['name'];
        }

        if (isset($params['phone_number'])) {
            $facetFilters[] = 'phone_numbers:' . preg_replace('/[^\d\+]/', '', $params['phone_number']);
        }

        if (isset($params['website'])) {
            $website = $this->cleanUrl($params['website']);
            if ($this->cleanUrl($params['website'])) {
                $facetFilters[] = 'domain:' . $website;
            }
        }

        if (isset($params['facebook'])) {
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
