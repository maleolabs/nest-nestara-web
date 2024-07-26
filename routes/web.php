<?php

use App\Http\Controllers\Admin\DashController;
use App\Http\Controllers\Admin\JenisPerizinanController;
use App\Http\Controllers\Admin\PemantauanController;
use App\Http\Controllers\Admin\ReportingController;
use App\Http\Controllers\BackOffice\DashboardController as BackOfficeDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Helper\FileController;
use App\Http\Controllers\Layanan\DashboardController as LayananDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\PerizinanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$role_placeholder = '{}';
$role_middleware = 'role:{}';
$pegawai_role = FRONT_OFFICE_ROLE . '|' . BACK_OFFICE_ROLE . '|' . ADMIN_ROLE;

$pemohonMiddleware = str_replace($role_placeholder, PEMOHON_ROLE, $role_middleware);
$adminMiddleware = str_replace($role_placeholder, ADMIN_ROLE, $role_middleware);
$frontOfcMiddleware = str_replace($role_placeholder, FRONT_OFFICE_ROLE, $role_middleware);
$backOfcMiddleware = str_replace($role_placeholder, BACK_OFFICE_ROLE, $role_middleware);
$pegawaiMiddleware = str_replace($role_placeholder, $pegawai_role, $role_middleware);

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/jenis-perizinan', [PerizinanController::class, 'jenisPerizinan'])->name('jenis-perizinan');

Route::get('/konfirmasi-izin/{izin_id}', [DashboardController::class, 'confirmIzin'])->name('confirm.izin');

Route::middleware('auth')->group(function() use ($pemohonMiddleware, $adminMiddleware, $frontOfcMiddleware, $backOfcMiddleware, $pegawaiMiddleware) {
    Route::middleware([$pemohonMiddleware])->group(function() {
        Route::get('/buat-izin-baru', [PerizinanController::class, 'create'])->name('create-izin');
        Route::get('/izin-saya', [PerizinanController::class, 'semuaIzin'])->name('user.izin');
        Route::middleware(['revisi_izin', 'show_izin'])->get('/revisi-izin/{izin_id}', [PerizinanController::class, 'revisiIzin'])->name('user.revisi-izin');
    });

    Route::middleware([$adminMiddleware])->group(function() {
        Route::prefix('admin')->group(function () {
            Route::get('/dashboard', [DashController::class, 'index'])->name('admin.dashboard');

            Route::get('/jenis-perizinan', [JenisPerizinanController::class, 'index'])->name('admin.jenis-perizinan.index');
        
            Route::prefix('/pemantauan')->group(function() {
                Route::get('/', [PemantauanController::class, 'index'])->name('admin.pemantauan.index');
            });
        
            Route::prefix('/reporting')->group(function() {
                Route::get('/', [ReportingController::class, 'index'])->name('admin.reporting.index');
            });
        });
    });

    Route::middleware([$frontOfcMiddleware])->group(function() {
        Route::prefix('layanan')->group(function() {
            Route::get('/dashboard', [LayananDashboardController::class, 'index'])->name('layanan.dashboard');
            Route::get('/pendaftaran', [LayananDashboardController::class, 'pendaftaran'])->name('layanan.pendaftaran');
            Route::get('/pendaftaran/create', [LayananDashboardController::class, 'pendaftaranCreate'])->name('layanan.pendaftaran.create');
            Route::get('/penyerahan', [LayananDashboardController::class, 'penyerahan'])->name('layanan.penyerahan');
        });
    });

    Route::middleware([$backOfcMiddleware])->group(function() {
        Route::prefix('/back-office')->group(function() {
            Route::get('/dashboard', [BackOfficeDashboardController::class, 'index'])->name('pegawai.dashboard');
            Route::get('/pendataan', [BackOfficeDashboardController::class, 'pendataan'])->name('pegawai.pendataan');
            Route::get('/rekomendasi', [BackOfficeDashboardController::class, 'rekomendasi'])->name('pegawai.rekomendasi');
            Route::get('/penetapan', [BackOfficeDashboardController::class, 'penetapan'])->name('pegawai.penetapan');

            Route::post('download-surat', [BackOfficeDashboardController::class, 'downloadSurat'])->name('pegawai.download-surat');
        });
    });

    Route::middleware(['dashboard'])->group(function() {
        Route::get('/dashboard', function() { return 'dashboard'; })->name('dashboard');
    });

    Route::middleware([$pegawaiMiddleware])->group(function() {
        Route::get('/semua-izin', [DashboardController::class, 'semuaIzin'])->name('izin.index');
        Route::get('/izin/preview/{izin_id}', [DashboardController::class, 'previewIzin'])->name('preview-izin');
        Route::get('/izin/count/{process}', [DashboardController::class, 'getCountByProcess'])->name('izin.count');
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