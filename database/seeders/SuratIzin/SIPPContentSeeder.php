<?php

namespace Database\Seeders\SuratIzin;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\utils\enums\SuratGroupsEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SIPPContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis_sipp = $this->jenisPerizinanService()->findByName('Surat Izin Praktek Perawat');
        if (!$jenis_sipp)
            dd('sipp tidak ditemukam');
        $content = [
            'judul' => 'SURAT IZIN PRAKTIK PERAWAT (SIPP)',

            'dasar' => 'Berdasarkan Peraturan Menteri Kesehatan Nomor 17 Tahun 2013 tentang Perubahan atas Peraturan Menteri Kesehatan Nomor HK.02.02/MENKES/148/I/2010 tentang Izin dan Penyelenggaraan Praktik Perawat yang ditindaklajuti dengan Peraturan Bupati Bone Bolango No 6 Tahun 2022 Tentang Pendelegasian Wewenang Penerbitan dan Penandatanganan Perizinan dan Non Perizinan Kepada Peyelenggara Pelayanan Terpadu Satu Pintu, yang bertandatangan di bawah ini, Kepala Dinas Penanaman Modal, Pelayanan Terpadu Satu Pintu Kabupaten Bone Bolango memberikan Izin Praktik kepada :',

            'keterangan' => 'Untuk menjalankan praktik sebagai Perawat di :',

            'tembusan' => [
                'Kepala Dinas Kesehatan Kabupaten Bone Bolango.',
                'Ketua Persatuan Perawat Nasional Indonesia Kabupaten Bone Bolango.',
                'Pertinggal.'
            ]
        ];
        $nama = "KONTEN-SURAT-{$jenis_sipp->id}";
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