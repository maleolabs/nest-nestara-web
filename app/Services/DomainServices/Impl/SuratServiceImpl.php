<?php

namespace App\Services\DomainServices\Impl;

use App\Services\AppService\Helpers\FileManager;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\Services\DomainServices\SuratService;
use App\utils\enums\SuratGroupsEnum;
use Carbon\Carbon;

class SuratServiceImpl implements SuratService {

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

    public function generateNomorSurat(int $jenis_perizinan_id): string
    {
        $nomor_surat = $this->getNomor();
        $generated_nomor_surat = $this->getFormat($jenis_perizinan_id, false);
        $generated_nomor_surat = str_replace('{nomor}', $nomor_surat, $generated_nomor_surat);
        return $generated_nomor_surat;
    }

    public function getFormat(int $jenis_perizinan_id, bool $clear_unreplace_format = true): string
    {
        $format_surat = $this->pengaturanService->find('format-surat');
        $instansi_surat = $this->pengaturanService->find('instansi-surat');
        if (!$format_surat || !$instansi_surat) {
            $this->generateDefaultFormatSurat();
            $this->getFormat($jenis_perizinan_id, $clear_unreplace_format);
        } 

        $jenis_nama = "JENIS-{$jenis_perizinan_id}";
        $jenis_nomor = "NO-JENIS-{$jenis_perizinan_id}";
        $jenis_izin = $this->pengaturanService->find($jenis_nama);
        $no_jenis_izin = $this->pengaturanService->find($jenis_nomor);
        if (!$jenis_izin || !$no_jenis_izin) {
            $this->generateDefaultJenisPerizinanFormat();
            $this->getFormat($jenis_perizinan_id, $clear_unreplace_format);
        }
        $no_jenis_izin = (int) $no_jenis_izin->value + 1;

        $today = Carbon::today();

        $generated_nomor_surat = str_replace([
            '{instansi}',
            '{jenis-izin}',
            '{no-jenis-izin}',
            '{tahun}'
        ], [
            $instansi_surat->value,
            $jenis_izin->value,
            str_pad($no_jenis_izin, 4, '0', STR_PAD_LEFT),
            $today->year
        ], $format_surat->value);

        $bagian_format_surat = explode('/', $generated_nomor_surat);
        foreach ($bagian_format_surat as $bagian) {
            if ($bagian == '{bulan-romawi}') {
                $generated_nomor_surat = str_replace($bagian, $this->getMonthRomawi($today->format('n')), $generated_nomor_surat);
            } else if ($bagian == '{bulan-angka}') {
                $generated_nomor_surat = str_replace($bagian, $today->translatedFormat('m'), $generated_nomor_surat);
            } else if ($bagian == '{bulan-nama}') {
                $generated_nomor_surat = str_replace($bagian, $today->translatedFormat('F'), $generated_nomor_surat);
            }
        }

        return $clear_unreplace_format ? str_replace('{nomor}', '', $generated_nomor_surat) : $generated_nomor_surat;
    }

    public function getActualFormat(): string
    {
        $format = $this->pengaturanService->findByNamaAndGroup('format-surat', SuratGroupsEnum::FORMAT_SURAT_GROUP);
        if (!$format) {
            $this->generateDefaultFormatSurat();
            $this->getActualFormat();
        }
        return $format->value;
    }

    public function getNomor(): int
    {
        $nomor_surat = $this->pengaturanService->find('nomor-surat');
        if (!$nomor_surat) return 1;
        return (int) $nomor_surat->value + 1;
    }

    public function updateNomorSurat(
        ?int $nomor_terakhir = null, 
        ?int $jenis_perizinan_id = null, 
        ?int $nomor_terakhir_jenis_perizinan = null): void
    {
        $nomor_surat = $this->pengaturanService->find('nomor-surat');
        if (!$nomor_surat) return;
        if (!$nomor_terakhir)
            $nomor_terakhir = (int) $nomor_surat->value + 1;

        $this->pengaturanService->updateOrCreate('nomor-surat', $nomor_terakhir, SuratGroupsEnum::FORMAT_SURAT_GROUP);

        $nomor_jenis_format = "NO-JENIS-{$jenis_perizinan_id}";
        $nomor_jenis = $this->pengaturanService->findByNamaAndGroup($nomor_jenis_format, SuratGroupsEnum::JENIS_SURAT_GROUP);
        if (!$nomor_jenis) return;
        if (!$nomor_terakhir_jenis_perizinan)
            $nomor_terakhir_jenis_perizinan = (int) $nomor_jenis->value + 1;

        $this->pengaturanService->updateOrCreate($nomor_jenis_format, $nomor_terakhir_jenis_perizinan, SuratGroupsEnum::JENIS_SURAT_GROUP);
    }

    public function getKopSurat(): array
    {
        $kop_surat = [];

        $logo_path = $this->pengaturanService->findByNamaAndGroup('logo-path', SuratGroupsEnum::KOP_SURAT_GROUP);
        $nama_pemerintah = $this->pengaturanService->findByNamaAndGroup('nama-pemerintah', SuratGroupsEnum::KOP_SURAT_GROUP);
        $nama_dinas = $this->pengaturanService->findByNamaAndGroup('nama-dinas', SuratGroupsEnum::KOP_SURAT_GROUP);
        $alamat = $this->pengaturanService->findByNamaAndGroup('alamat', SuratGroupsEnum::KOP_SURAT_GROUP);
        $website = $this->pengaturanService->findByNamaAndGroup('website', SuratGroupsEnum::KOP_SURAT_GROUP);

        if (!$logo_path || !$nama_pemerintah || !$nama_dinas || !$alamat || !$website) {
            $this->generateDefaultKopSurat();
            return $this->getKopSurat();
        }

        $kop_surat['logo_path'] = $logo_path->value;
        $kop_surat['nama_pemerintah'] = $nama_pemerintah->value;
        $kop_surat['nama_dinas'] = $nama_dinas->value;
        $kop_surat['alamat'] = $alamat->value;
        $kop_surat['website'] = $website->value;

        return $kop_surat;
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

    private function getMonthRomawi(int $month): string
    {
        $romawi = $this->monthRomawi();
        return $romawi[$month];
    }

    private function monthRomawi(): array
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