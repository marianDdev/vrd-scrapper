<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WebScrapingController;
use Illuminate\Support\Facades\Route;

Route::post('/scrape', [WebScrapingController::class, 'startScraping'])->name('scrape');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
