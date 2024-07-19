<?php

namespace Database\Seeders\SuratIzin;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\utils\enums\SuratGroupsEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SIKPMDIKContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis_sikpmdik = $this->jenisPerizinanService()->findByName('Surat Izin Kerja Perekam Medis Dan Informasi Kesehatan');
        if (!$jenis_sikpmdik)
            dd('sikpmdik tidak ditemukam');
        $content = [
            'judul' => 'SURAT IZIN KERJA PEREKAM MEDIS DAN INFORMASI KESEHATAN',

            'dasar' => 'Berdasarkan Undang-undang No.36 Tahun 2014 Tentang Tenaga Kesehatan, Peraturan Menteri Kesehatan RI Nomor 55 Tahun 2013 tentang Penyelenggaraan Pekerjaan Perekam Medis danPeraturan Bupati Bone Bolango No 6 Tahun 2022 Tentang Pendelegasian Wewenang Penerbitan dan Penandatanganan Perizinan dan Non Dinas Penanaman Modal, Pelayanan Terpadu Satu Pintu Kabupaten Bone Bolango memberikan Izin Kerja Perekam Medis dan Informasi Kesehatan kepada :',

            'keterangan' => 'Diberikan izin kerja untuk melakukan pekerjaan Perekam Medis dan Informasi Kesehatan pada:',

            'tembusan' => [
                'Kepala Dinas Kesehatan Kabupaten Bone Bolango.',
                'Ketua Ikatan Ahli Kesehatan Masyarakat Indonesia.',
                'Pertinggal.'
            ]
        ];
        $nama = "KONTEN-SURAT-{$jenis_sikpmdik->id}";
        $this->pengaturanService()->updateOrCreate($nama, $content, SuratGroupsEnum::KONTEN_SURAT_GROUP);
    }
 
    private function pengaturanService(): PengaturanService
    {
        return app(PengaturanService::class);
    }

    private function jenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }
    
}