<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataEntriController;
use App\Http\Controllers\Api\IdentityDocumentController;
use App\Http\Controllers\Api\JenisPerizinanController;
use App\Http\Controllers\Api\OcrController;
use App\Http\Controllers\Api\PemohonController;
use App\Http\Controllers\Api\PerizinanController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Helper\FileController;
use App\Http\Middleware\VerifySignedFileUrl;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt.auth'])->group(function () {
    Route::prefix('/jenis-perizinan')->group(function () {
        Route::get('/', [JenisPerizinanController::class, 'getAll']);
        Route::get('/{id}', [JenisPerizinanController::class, 'find']);
        Route::get('/{id}/persyaratan', [JenisPerizinanController::class, 'getPersyaratan']);
    });

    Route::prefix('/izin')->group(function () {
        Route::get('/', [PerizinanController::class, 'get']);
        Route::post('/', [PerizinanController::class, 'create']);
        Route::get('/{id}', [PerizinanController::class, 'find']);
        Route::get('/{id}/persyaratan', [PerizinanController::class, 'getPersyaratan']);
        Route::patch('/{id}/cancel', [PerizinanController::class, 'cancelRegistration']);
        Route::delete('/{id}/delete', [PerizinanController::class, 'delete']);
    });

    Route::prefix('/address')->group(function () {
        Route::get('/regions/{type}/{parent_id}', [AddressController::class, 'getRegions']);
        Route::get('/find-by-id/{type}/{id}', [AddressController::class, 'findById']);
        Route::get('/find-by-name/{name}', [AddressController::class, 'findByName']);
        Route::get('/get-all', [AddressController::class, 'getAll']);
    });

    Route::prefix('/pemohon')->group(function () {
        Route::post('/', [PemohonController::class, 'store']);
        Route::get('/', [PemohonController::class, 'find']);
    });

    Route::prefix('/ocr')->group(function () {
        Route::post('/ktp', [OcrController::class, 'store']);
        Route::get('/ktp/{request_id}', [OcrController::class, 'status'])->where('request_id', '[A-Za-z0-9-]+');
    });
    Route::prefix('/identity-documents')->group(function () {
        Route::post('/', [IdentityDocumentController::class, 'store']);
        Route::get('/{check_id}', [IdentityDocumentController::class, 'status'])->whereUuid('check_id');
    });

    Route::prefix('/user')->group(function () {
        Route::put('/update-profil', [UserController::class, 'updateProfil']);
        Route::put('/update-password', [UserController::class, 'updatePassword']);
        Route::delete('/account', [UserController::class, 'deleteAccount']);
    });
});

Route::prefix('data-entri')->group(function () {
    Route::get('/{jenis_perizinan_id}', [DataEntriController::class, 'getByJenisPerizinanId']);
});

Route::prefix('/files')->middleware(VerifySignedFileUrl::class)->group(function () {
    Route::get('/{file_name}', [FileController::class, 'render'])->name('api.render-file');
});

Route::prefix('/auth')->group(function () {
    Route::middleware(['api'])->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware(['jwt.auth'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('jwt.refresh');
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::post('/verify-client-token/{token}', [TokenController::class, 'verifyClientToken']);
Route::post('/generate-client-token', [TokenController::class, 'generateToken']);
