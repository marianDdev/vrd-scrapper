<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $searchQuery = $request->get('query', ''); // Get the search query input

        $companies = Company::search($searchQuery, function($algolia, $query, $options) use ($request) {
            if ($request->has('phone')) {
                $options['facetFilters'] = ["phone_numbers:{$request->phone}"];
            }
            if ($request->has('facebook')) {
                $options['facetFilters'][] = ["social_media_links.url:{$request->facebook}"];
            }
            return $algolia->search($query, $options);
        })->paginate(10);

        return response()->json($companies);
    }
}
