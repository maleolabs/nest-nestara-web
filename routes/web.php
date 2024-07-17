<?php

use App\Http\Controllers\Admin\BackOfficeController;
use App\Http\Controllers\Admin\DashController;
use App\Http\Controllers\Admin\PemantauanController;
use App\Http\Controllers\Admin\PendaftaranController;
use App\Http\Controllers\Admin\PenyerahanController;
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\BackOffice\DashboardController as BackOfficeDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Helper\FileController;
use App\Http\Controllers\Layanan\DashboardController as LayananDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PerizinanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/jenis-perizinan', [PerizinanController::class, 'jenisPerizinan'])->name('jenis-perizinan');

Route::middleware('auth')->group(function() {
    Route::middleware(['role:pemohon'])->group(function() {
        Route::get('/buat-izin-baru', [PerizinanController::class, 'create'])->name('create-izin');
        Route::get('/izin-saya', [PerizinanController::class, 'semuaIzin'])->name('user.izin');
        Route::middleware(['revisi_izin', 'show_izin'])->get('/revisi-izin/{izin_id}', [PerizinanController::class, 'revisiIzin'])->name('user.revisi-izin');
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
            Route::get('/dashboard', [LayananDashboardController::class, 'index'])->name('layanan.dashboard');
            Route::get('/pendaftaran', [LayananDashboardController::class, 'pendaftaran'])->name('layanan.pendaftaran');
            Route::get('/pendaftaran/create', [LayananDashboardController::class, 'pendaftaranCreate'])->name('layanan.pendaftaran.create');
            Route::get('/penyerahan', [LayananDashboardController::class, 'penyerahan'])->name('layanan.penyerahan');
        });
    });

    Route::middleware(['role:pegawai'])->group(function() {
        Route::prefix('/back-office')->group(function() {
            Route::get('/dashboard', [BackOfficeDashboardController::class, 'index'])->name('pegawai.dashboard');
            Route::get('/pendataan', [BackOfficeDashboardController::class, 'pendataan'])->name('pegawai.pendataan');
            Route::get('/tim-teknis', [BackOfficeDashboardController::class, 'timTeknis'])->name('pegawai.tim-teknis');
            Route::get('/penetapan', [BackOfficeDashboardController::class, 'penetapan'])->name('pegawai.penetapan');
        });
    });

    Route::middleware(['dashboard'])->group(function() {
        Route::get('/dashboard', function() { return 'dashboard'; })->name('dashboard');
    });

    Route::middleware(['role:admin|pengelola_layanan|pegawai'])->group(function() {
        Route::get('/izin/preview/{izin_id}', [DashboardController::class, 'previewIzin'])->name('preview-izin');
    });
    

    Route::get('/files/download/{file_name}', [FileController::class, 'download'])->name('download-file');
    Route::get('/files/{file_name}', [FileController::class, 'render'])->name('render-file');

    Route::middleware(['profile'])->group(function() {
        Route::get('/profile', function () { return 'profile'; })->name('profile');
        Route::get('/profile/admin', [ProfileController::class, 'admin'])->name('profile.admin');
        Route::get('/profile/pengelola_layanan', [ProfileController::class, 'pengelola_layanan'])->name('profile.pengelola_layanan');
        Route::get('/profile/pegawai', [ProfileController::class, 'pegawai'])->name('profile.pegawai');
        Route::get('/profile/pemohon', [ProfileController::class, 'pemohon'])->name('profile.pemohon');
    });

});