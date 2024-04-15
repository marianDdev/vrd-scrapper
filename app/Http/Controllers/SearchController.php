<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\Search\SearchServiceInterface;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function index(SearchRequest $request, SearchServiceInterface $service): JsonResponse
    {
        return new JsonResponse($service->search($request->validated()));
    }
}
