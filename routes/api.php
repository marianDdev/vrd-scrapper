<?php

use App\Http\Controllers\SearchController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\WebScrapingController;
use Illuminate\Support\Facades\Route;

Route::post('/scrape', [WebScrapingController::class, 'startScraping'])->name('scrape');
Route::get('/companies', [SearchController::class, 'index'])->name('companies');
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
