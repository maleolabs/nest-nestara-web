<?php

use App\Http\Controllers\Api\JenisPerizinanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataEntriController;
use App\Http\Controllers\Api\PerizinanController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Helper\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt.auth'])->group(function() {
    Route::prefix('/jenis-perizinan')->group(function () {
        Route::get('/', [JenisPerizinanController::class, 'getAll']);
        Route::get('/{id}', [JenisPerizinanController::class, 'find']);
        Route::get('/{id}/persyaratan', [JenisPerizinanController::class, 'getPersyaratan']);
    });

    Route::prefix('/izin')->group(function() {
        Route::get('/', [PerizinanController::class, 'get']);
        Route::post('/', [PerizinanController::class, 'create']);
        Route::get('/{id}', [PerizinanController::class, 'find']);
        Route::get('/{id}/persyaratan', [PerizinanController::class, 'getPersyaratan']);    
    });
});

Route::prefix('data-entri')->group(function () {
    Route::get('/{jenis_perizinan_id}', [DataEntriController::class, 'getByJenisPerizinanId']);
});

Route::prefix('/files')->group(function () {
    Route::get('/{file_name}', [FileController::class, 'render'])->name('api.render-file');
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

Route::post('/verify-client-token/{token}', [TokenController::class, 'verifyClientToken']);
Route::post('/generate-client-token', [TokenController::class, 'generateToken']);