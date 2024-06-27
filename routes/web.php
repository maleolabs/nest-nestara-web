<?php

use App\Http\Controllers\Dashboard\BackOfficeController;
use App\Http\Controllers\Dashboard\DashController;
use App\Http\Controllers\Dashboard\PemantauanController;
use App\Http\Controllers\Dashboard\PendaftaranController;
use App\Http\Controllers\Dashboard\PenyerahanController;
use App\Http\Controllers\Dashboard\ReportingController;
use App\Http\Controllers\Helper\FileController;
use App\Http\Controllers\User\PerizinanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/jenis-perizinan', [PerizinanController::class, 'jenisPerizinan'])->name('jenis-perizinan');

Route::middleware('auth')->group(function() {
    Route::get('/buat-izin-baru', [PerizinanController::class, 'create'])->name('create-izin');
    Route::get('/izin-saya', [PerizinanController::class, 'semuaIzin'])->name('user.izin');
    
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashController::class, 'index'])->name('dashboard');
        
        Route::prefix('/pendaftaran')->group(function() {
            Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
            Route::get('/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
        });
    
        Route::prefix('/back-office')->group(function() {
            Route::get('/', [BackOfficeController::class, 'index'])->name('back-office.index');
        });
    
        Route::prefix('/penyerahan-izin')->group(function() {
            Route::get('/', [PenyerahanController::class, 'index'])->name('penyerahan.index');
        });
    
        Route::prefix('/pemantauan')->group(function() {
            Route::get('/', [PemantauanController::class, 'index'])->name('pemantauan.index');
        });
    
        Route::prefix('/reporting')->group(function() {
            Route::get('/', [ReportingController::class, 'index'])->name('reporting.index');
        });
    });

    Route::get('/files/download/{file_name}', [FileController::class, 'download'])->name('download-file');
    Route::get('/files/{file_name}', [FileController::class, 'render'])->name('render-file');
});