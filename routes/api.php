<?php

use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SerieController;
use Illuminate\Support\Facades\Route;

Route::apiResource('movies', MovieController::class)->only(['index', 'show']);
Route::apiResource('series', SerieController::class)->only(['index', 'show']);
Route::apiResource('genres', GenreController::class)->only(['index']);
