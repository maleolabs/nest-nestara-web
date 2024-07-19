<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use App\Services\DomainServices\PengaturanService;
use App\Services\DomainServices\SuratService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengaturan::truncate();
        $this->createPengaturanSurat();   
    }

    private function createPengaturanSurat()
    {
        $this->suratService()->generateDefaultFormatSurat();
        $this->suratService()->generateDefaultJenisPerizinanFormat();
        $this->suratService()->setDefaultJenisSuratTemplate();
        $this->suratService()->generateDefaultKopSurat();
    }

    private function pengaturanService(): PengaturanService
    {
        return app(PengaturanService::class);
    }

    private function suratService(): SuratService
    {
        return app(SuratService::class);
    }
    
}