<?php

namespace App\Services\DomainServices\Helpers;

use App\Services\AppService\Helpers\FileManager;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\utils\enums\SuratGroupsEnum;

class PengaturanSuratGenerator
{

    protected PengaturanService $pengaturanService;

    protected JenisPerizinanService $jenisPerizinanService;

    protected FileManager $fileManager;

    public function __construct(
        PengaturanService $pengaturanService,
        JenisPerizinanService $jenisPerizinanService,
        FileManager $fileManager
    ) {
        $this->pengaturanService = $pengaturanService;
        $this->jenisPerizinanService = $jenisPerizinanService;
        $this->fileManager = $fileManager;
    }

    public function generateDefaultFormatSurat(): void
    {
        $formatSurat = '{nomor}/{instansi}/{jenis-izin}/{no-jenis-izin}/{bulan-romawi}/{tahun}';
        $this->pengaturanService->updateOrCreate('nomor-surat', 0, SuratGroupsEnum::FORMAT_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('instansi-surat', 'DPMPTSP-BB', SuratGroupsEnum::FORMAT_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('format-surat', $formatSurat, SuratGroupsEnum::FORMAT_SURAT_GROUP);

        $bagianFormatSurat = [
            'nomor' => '{nomor}',
            'instansi' => '{instansi}',
            'jenis-izin' => '{jenis-izin}',
            'no-jenis-izin' => '{no-jenis-izin}',
            'bulan' => [
                '{bulan-romawi}',
                '{bulan-angka}',
                '{bulan-nama}'
            ],
            'tahun' => '{tahun}'
        ];
        $this->pengaturanService->updateOrCreate('bagian-format-surat', $bagianFormatSurat, SuratGroupsEnum::FORMAT_SURAT_GROUP);
    }

    public function generateDefaultKopSurat(): void
    {
        $this->pengaturanService->updateOrCreate('logo-path', 'storage/pdf-assets/cop-icon.png', SuratGroupsEnum::KOP_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('nama-pemerintah', 'PEMERINTAH KABUPATEN BONE BOLANGO', SuratGroupsEnum::KOP_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('nama-dinas', 'DINAS PENANAMAN MODAL PELAYANAN TERPADU SATU PINTU', SuratGroupsEnum::KOP_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('alamat', 'Pusat Pemerintahan Jl. Prof. Dr. Ing. B.J. Habibie Desa Ulanta Kecamatan Suwawa', SuratGroupsEnum::KOP_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('website', route('welcome'), SuratGroupsEnum::KOP_SURAT_GROUP);
    }

    public function generateDefaultJenisPerizinanFormat(): void
    {
        $setupdatajenis = [];
        $setupdatajenisnomor = [];
        foreach ($this->jenisPerizinanService->getAll() as $jenisPerizinan) {
            $jenis_format = '';
            $jenis_words = explode(' ', $jenisPerizinan->nama);
            if (count($jenis_words) > 2) {
                foreach ($jenis_words as $jenis_word) {
                    $first_letter = substr($jenis_word, 0, 1);
                    if (ctype_alpha($first_letter)) $jenis_format .= strtoupper($first_letter);
                }
            } else
                $jenis_format = strtoupper(str_replace(' ', '-', $jenisPerizinan->nama));

            $setupdatajenis[] = [
                'group' => SuratGroupsEnum::JENIS_SURAT_GROUP,
                'nama' => "JENIS-{$jenisPerizinan->id}",
                'value' => $jenis_format,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $setupdatajenisnomor[] = [
                'group' => SuratGroupsEnum::JENIS_SURAT_GROUP,
                'nama' => "NO-JENIS-{$jenisPerizinan->id}",
                'value' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $this->pengaturanService->createMany($setupdatajenis);
        $this->pengaturanService->createMany($setupdatajenisnomor);
    }

    public function setDefaultJenisSuratTemplate(): void
    {
        $setup_jenis_templates = [];
        $all_jenis = $this->jenisPerizinanService->getAll();

        foreach ($all_jenis as $jenis) {
            $templates = $this->fileManager->getFile()
                ->folders($jenis->template_folder)
                ->getResourceFile()
                ->getFilesName();

            if (!isset($templates[0]))
                continue;

            $value = $templates[0];

            $setup_jenis_templates[] = [
                'group' => SuratGroupsEnum::SURAT_TEMPLATE_GROUP,
                'nama' => "TEMPLATE-SURAT-{$jenis->id}",
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $this->pengaturanService->createMany($setup_jenis_templates);
    }

    protected function getMonthRomawi(int $month): string
    {
        $romawi = $this->monthRomawi();
        return $romawi[$month];
    }

    protected function monthRomawi(): array
    {
        return [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
    }

}