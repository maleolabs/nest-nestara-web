<?php

namespace Database\Seeders\SuratIzin;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\utils\enums\SuratGroupsEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IPMContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis_ipm = $this->jenisPerizinanService()->findByName('IZIN PENELITIAN MAHASISWA');
        if (!$jenis_ipm)
            dd('ipm tidak ditemukam');
        $content = [
            'judul' => 'SURAT KETERANGAN PENELITIAN',
            'dasar' => [
                'Peraturan Menteri Dalam Negeri Nomor 3 Tahun 2018 Tentang Surat Keterangan Penelitian.',
                'Peraturan Bupati Bone Bolango No 6 Tahun 2022 Tentang Pendelegasian Wewenang Penerbitan dan Penandatanganan Perizinan dan Non Perizinan Kepada Peyelenggara Pelayanan Terpadu Satu Pintu.'
            ],
            'membaca' => 'Dokumen Protokol/Proposal Penelitian yang diajukan secara lengkap.',
            'keterangan' => 'Pada prinsipnya rencana penelitian yang bersangkutan tidak bertentangan dengan Pancasila dan Undang-undang Dasar 1945 serta yang bersangkutan disetujui untuk melanjutkan penelitian dengan ketentuan :',
            'ketentuan' => [
                'Ruang Lingkup dan Lokasi Penelitian yang bersangkutan bedasarkan pada kerangka kerja/proposal/protokol yang disampaikan.',
                'Peneliti harus menyampaikan hasil penelitiannya kepada Pemerintah Daerah Kabupaten Bone Bolango melalui Dinas Penanaman Modal Pelayanan Terpadu Satu Pintu setelah selesai melakukan penelitian.',
                'Surat Keterangan Penelitian ini berlaku sampai dengan 6 JUNI 2025 dan selanjutnya dapat diperpanjang kembali apabila yang bersangkutan belum selesai melakukan penelitian'
            ],
            'penutup' => 'Demikian Surat Keterangan Penelitian ini diberikan untuk dipergunakan sebagaimana mestinya dalam melakukan penelitian',
            'tembusan' => [
                'Bupati Bone Bolango di Suwawa sebagai laporan.',
                'Universitas/Sekolah/Lembaga yang bersangkutan.'
            ]
        ];
        $nama = "KONTEN-SURAT-{$jenis_ipm->id}";
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