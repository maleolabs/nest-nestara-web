<?php

use App\Http\Controllers\Admin\BackOfficeController;
use App\Http\Controllers\Admin\DashController;
use App\Http\Controllers\Admin\PemantauanController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\PenyerahanController;
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\BackOffice\DashboardController as BackOfficeDashboardController;
use App\Http\Controllers\Helper\FileController;
use App\Http\Controllers\Layanan\DashboardController;
use App\Http\Controllers\User\PerizinanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/jenis-perizinan', [PerizinanController::class, 'jenisPerizinan'])->name('jenis-perizinan');

Route::middleware('auth')->group(function() {
    Route::middleware(['role:pemohon'])->group(function() {
        Route::get('/buat-izin-baru', [PerizinanController::class, 'create'])->name('create-izin');
        Route::get('/izin-saya', [PerizinanController::class, 'semuaIzin'])->name('user.izin');
    });

    Route::middleware(['role:admin'])->group(function() {
        Route::prefix('admin')->group(function () {
            Route::get('/dashboard', [DashController::class, 'index'])->name('admin.dashboard');
            
            Route::prefix('/pendaftaran')->group(function() {
                Route::get('/', [PendaftaranController::class, 'index'])->name('admin.pendaftaran.index');
                Route::get('/create', [PendaftaranController::class, 'create'])->name('admin.pendaftaran.create');
            });

            Route::prefix('/back-office')->group(function() {
                Route::get('/', [BackOfficeController::class, 'index'])->name('admin.back-office.index');
            });
        
            Route::prefix('/penyerahan-izin')->group(function() {
                Route::get('/', [PenyerahanController::class, 'index'])->name('admin.penyerahan.index');
            });
        
            Route::prefix('/pemantauan')->group(function() {
                Route::get('/', [PemantauanController::class, 'index'])->name('admin.pemantauan.index');
            });
        
            Route::prefix('/reporting')->group(function() {
                Route::get('/', [ReportingController::class, 'index'])->name('admin.reporting.index');
            });
        });
    });

    Route::middleware(['role:pengelola_layanan'])->group(function() {
        Route::prefix('layanan')->group(function() {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('layanan.dashboard');
            Route::get('/pendaftaran', [DashboardController::class, 'pendaftaran'])->name('layanan.pendaftaran');
            Route::get('/pendaftaran/create', [DashboardController::class, 'pendaftaranCreate'])->name('layanan.pendaftaran.create');
            Route::get('/penyerahan', [DashboardController::class, 'penyerahan'])->name('layanan.penyerahan');
        });
    });

    Route::middleware(['role:pegawai'])->group(function() {
        Route::prefix('/back-office')->group(function() {
            Route::get('/dashboard', [BackOfficeDashboardController::class, 'index'])->name('pegawai.dashboard');
        });
    });

    Route::middleware(['dashboard'])->group(function() {
        Route::get('/dashboard', function() {
            return 'dashboard';
        })->name('dashboard');
    });
    

    Route::get('/files/download/{file_name}', [FileController::class, 'download'])->name('download-file');
    Route::get('/files/{file_name}', [FileController::class, 'render'])->name('render-file');
});