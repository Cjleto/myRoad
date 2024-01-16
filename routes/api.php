<?php

use App\Exceptions\RouteNotFound;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\TourController;
use App\Http\Controllers\Api\V1\TravelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', LoginController::class)->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    //Route::post('travels/store', [TravelController::class, 'store']);
    Route::apiResource('travels', TravelController::class)->except(['destroy']);
    Route::apiResource('tours', TourController::class)->except(['destroy']);

});

Route::get('travel/{travel:slug}/tours', [TourController::class, 'toursByTravelSlug']);

Route::fallback(function () {
    throw new RouteNotFound('Page Not Found. If error persists, contact support', 404);
});
