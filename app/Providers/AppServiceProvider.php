<?php

namespace App\Providers;

use App\Services\APIClient\Impl\EmsifaAPIService;
use App\Services\APIClient\RegionAPIService;
use App\Services\AppService\EncryptionService;
use App\Services\AppService\FileService;
use App\Services\AppService\Helpers\FileManager;
use App\Services\AppService\Helpers\Impl\LocalStorageFileManager;
use App\Services\AppService\Impl\EncryptionServiceImpl;
use App\Services\AppService\Impl\FileServiceImpl;
use App\Services\AppService\Impl\PdfTemplateServiceImpl;
use App\Services\AppService\PdfTemplateService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\EntriDataService;
use App\Services\DomainServices\Helpers\GetIzin;
use App\Services\DomainServices\Impl\DokumenPemohonServiceImpl;
use App\Services\DomainServices\Impl\EntriDataServiceImpl;
use App\Services\DomainServices\Impl\JenisPerizinanServiceImpl;
use App\Services\DomainServices\Impl\PegawaiServiceImpl;
use App\Services\DomainServices\PemohonService;
use App\Services\DomainServices\Impl\PemohonServiceImpl;
use App\Services\DomainServices\Impl\PengaturanServiceImpl;
use App\Services\DomainServices\Impl\PerizinanServiceImpl;
use App\Services\DomainServices\Impl\RoleServiceImpl;
use App\Services\DomainServices\Impl\StatusIzinServiceImpl;
use App\Services\DomainServices\Impl\SuratServiceImpl;
use App\Services\DomainServices\Impl\SyaratIzinServiceImpl;
use App\Services\DomainServices\Impl\UserServiceImpl;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PegawaiService;
use App\Services\DomainServices\PengaturanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\RoleService;
use App\Services\DomainServices\StatusIzinService;
use App\Services\DomainServices\SuratService;
use App\Services\DomainServices\SyaratIzinService;
use App\Services\DomainServices\UserService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function provides()
    {
        return [
            RegionAPIService::class,
            PemohonService::class,
            FileService::class,
            SyaratIzinService::class,
            JenisPerizinanService::class,
            DokumenPemohonService::class,
            PerizinanService::class,
            StatusIzinService::class,
            UserService::class,
            RoleService::class,
            EntriDataService::class,
            PegawaiService::class,
            PengaturanService::class,
            SuratService::class,
            EncryptionService::class,
            PdfTemplateService::class,
            FileManager::class,
        ];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FileService::class, FileServiceImpl::class);
        $this->app->singleton(RegionAPIService::class, EmsifaAPIService::class);
        $this->app->singleton(PemohonService::class, PemohonServiceImpl::class);
        $this->app->singleton(SyaratIzinService::class, SyaratIzinServiceImpl::class);
        $this->app->singleton(JenisPerizinanService::class, JenisPerizinanServiceImpl::class);
        $this->app->singleton(DokumenPemohonService::class, DokumenPemohonServiceImpl::class);
        $this->app->singleton(PerizinanService::class, PerizinanServiceImpl::class);
        $this->app->singleton(StatusIzinService::class, StatusIzinServiceImpl::class);
        $this->app->singleton(UserService::class, UserServiceImpl::class);
        $this->app->singleton(RoleService::class, RoleServiceImpl::class);
        $this->app->singleton(EntriDataService::class, EntriDataServiceImpl::class);
        $this->app->singleton(PegawaiService::class, PegawaiServiceImpl::class);
        $this->app->singleton(PengaturanService::class, PengaturanServiceImpl::class);
        $this->app->singleton(SuratService::class, SuratServiceImpl::class);
        $this->app->singleton(EncryptionService::class, EncryptionServiceImpl::class);
        $this->app->singleton(PdfTemplateService::class, PdfTemplateServiceImpl::class);
        $this->app->singleton(FileManager::class, LocalStorageFileManager::class);

        $this->app->singleton(GetIzin::class, function () {
            return new GetIzin();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
