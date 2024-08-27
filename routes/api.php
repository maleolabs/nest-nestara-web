<?php

use App\Http\Controllers\Api\JenisPerizinanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/jenis-perizinan')->group(function () {
    Route::get('/', [JenisPerizinanController::class, 'getAll']);
    Route::get('/{id}/persyaratan', [JenisPerizinanController::class, 'getPersyaratan']);
});

Route::prefix('/users')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
});