<?php

namespace App\Http\Controllers;

use App\Services\Statistics\StatisticsServiceInterface;
use Illuminate\Http\JsonResponse;

class StatisticsController extends Controller
{
    public function index(StatisticsServiceInterface $service): JsonResponse
    {
        return new JsonResponse($service->getStatistics());
    }
}
