<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\TourController;
use App\Http\Controllers\TravelController;
use Illuminate\Http\Request;
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

Route::post('login', LoginController::class);

Route::middleware(['auth:sanctum'])->group(function () {
   //Route::post('travels/store', [TravelController::class, 'store']);
   Route::apiResource('travels', TravelController::class)->except(['destroy']);
   Route::apiResource('tours', TourController::class)->except(['destroy']);

});


Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact support'], 404);
});
