<?php

use App\Http\Controllers\Api\JenisPerizinanController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt.auth'])->group(function() {
    Route::prefix('/jenis-perizinan')->group(function () {
        Route::get('/', [JenisPerizinanController::class, 'getAll']);
        Route::get('/{id}/persyaratan', [JenisPerizinanController::class, 'getPersyaratan']);
    });
});

Route::prefix('/auth')->group(function () {
    Route::middleware(['api'])->group(function() {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware(['jwt.auth'])->group(function() {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');
        Route::get('/me', [AuthController::class, 'me']);
    });
});