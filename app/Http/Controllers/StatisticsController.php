<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatisticsResource;
use App\Services\Statistics\StatisticsServiceInterface;

class StatisticsController extends Controller
{
    public function index(StatisticsServiceInterface $service): StatisticsResource
    {
        return new StatisticsResource($service->getStatistics());
    }
}
