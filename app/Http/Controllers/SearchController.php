<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultResource;
use App\Services\Search\SearchServiceInterface;

class SearchController extends Controller
{
    public function index(SearchRequest $request, SearchServiceInterface $service): SearchResultResource
    {
        return new SearchResultResource($service->search($request->validated()));
    }
}
