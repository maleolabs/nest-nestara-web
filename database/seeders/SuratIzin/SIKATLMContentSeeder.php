<?php

namespace Database\Seeders\SuratIzin;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\utils\enums\SuratGroupsEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SIKATLMContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis_sikatlm = $this->jenisPerizinanService()->findByName('Surat Izin Kerja Ahli Teknologi Laboratorium Medik (SIP-ATLM)');
        if (!$jenis_sikatlm)
            dd('sikatlm tidak ditemukam');
        $content = [
            'judul' => 'SURAT IZIN PRAKTIK AHLI TEKNOLOGI LABORATORIUM MEDIK',

            'dasar' => 'Berdasarkan Peraturan Menteri Kesehatan Nomor 42 Tahun 2015 tentang Izin dan Penyelenggaraan Praktik Ahli Teknologi Laboratorium Medik, dan Peraturan Bupati Bone Bolango Nomor 6 Tahun 2022 Tentang Pendelegasian Wewenang Penerbitan dan Penandatanganan Perizinan dan Non Perizinan Kepada Penyelenggara Pelayanan Terpadu Satu Pintu Kabupaten Bone Bolango , memberikan Izin Praktik Kepada :',

            'keterangan' => 'Untuk bekerja sebagai Ahli Teknologi Laboratorium Medik',

            'tembusan' => [
                'Kepala Dinas Kesehatan Kabupaten Bone Bolango.',
                'Arsip.'
            ]
        ];
        $nama = "KONTEN-SURAT-{$jenis_sikatlm->id}";
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
