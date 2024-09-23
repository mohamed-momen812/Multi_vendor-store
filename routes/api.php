<?php

use App\Http\Controllers\Api\AccessTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user(); // return the current authenticated user
    })->middleware('auth:sanctum');

    // auth middleware inside controller
    Route::apiResource('products', ProductController::class);

    Route::post('auth/access-tokens', [AccessTokenController::class, 'store'])
        ->middleware('guest:sanctum'); // should be guest to access this routes

    // {token?} optional param
    Route::delete('auth/access-tokens/{token?}', [AccessTokenController::class, 'destroy'])
        ->middleware('auth:sanctum'); // should be auth to access this routes
});



