<?php

namespace Database\Seeders;

use App\Services\DomainServices\PengaturanService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createPengaturanSurat();   
    }

    private function createPengaturanSurat()
    {
        $formatSurat = '{nomor}/{instansi}/{jenis-izin}/{no-jenis-izin}/{bulan-romawi/{tahun}';
        $this->pengaturanService()->updateOrCreate('nomor-surat', 0, 'surat');
        $this->pengaturanService()->updateOrCreate('instansi-surat', 'DPMPTSP-BB', 'surat');
        $this->pengaturanService()->updateOrCreate('format-surat', $formatSurat, 'surat');
    }

    private function pengaturanService(): PengaturanService
    {
        return app(PengaturanService::class);
    }
    
}