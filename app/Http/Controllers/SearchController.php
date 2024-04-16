<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\Search\SearchServiceInterface;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function index(SearchRequest $request, SearchServiceInterface $service): JsonResponse
    {
        $validated = $request->validated();
        $result    = $service->search($validated);

        if (!count($result)) {
            return new JsonResponse(
                sprintf(
                    'No results found for %s keyword',
                    $validated['keyword']
                ),
                404
            );
        }

        return new JsonResponse($service->search($validated));
    }
}
