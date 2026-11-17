<?php

use App\Http\Controllers\MobileAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [MobileAuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/login', [MobileAuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [MobileAuthController::class, 'me']);
        Route::post('/logout', [MobileAuthController::class, 'logout']);
    });
});
