<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\Services\DomainServices\SuratService;
use Carbon\Carbon;

class SuratServiceImpl implements SuratService {

    protected PengaturanService $pengaturanService;

    protected JenisPerizinanService $jenisPerizinanService;

    public function __construct(
        PengaturanService $pengaturanService,
        JenisPerizinanService $jenisPerizinanService
    ) {
        $this->pengaturanService = $pengaturanService;
        $this->jenisPerizinanService = $jenisPerizinanService;
    }

    public function generateNomorSurat(int $jenis_perizinan_id): string
    {
        $format_surat = $this->pengaturanService->find('format-surat');
        $nomor_surat = $this->pengaturanService->find('nomor-surat');
        $instansi_surat = $this->pengaturanService->find('instansi-surat');
        if (!$format_surat || !$nomor_surat || !$instansi_surat) $this->generateDefaultFormatSurat();

        $nomor_surat = $nomor_surat->value + 1;

        $jenis_nama = "JENIS-{$jenis_perizinan_id}";
        $jenis_nomor = "NO-JENIS-{$jenis_perizinan_id}";
        $jenis_izin = $this->pengaturanService->findByNamaAndGroup($jenis_nama, 'izin');
        $no_jenis_izin = $this->pengaturanService->findByNamaAndGroup($jenis_nomor, 'izin');
        if (!$jenis_izin ||!$no_jenis_izin) $this->generateDefaultJenisPerizinanFormat();

        $today = Carbon::today();

        $generated_nomor_surat = str_replace([
            '{nomor}',
            '{instansi}',
            '{jenis-izin}',
            '{no-jenis-izin}',
            '{tahun}'
        ], [
            $nomor_surat,
            $instansi_surat->value,
            $jenis_izin->value,
            $no_jenis_izin->value,
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

        return $generated_nomor_surat;
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

        $this->pengaturanService->updateOrCreate('nomor-surat', $nomor_terakhir,'surat');

        $nomor_jenis_format = "NO-JENIS-{$jenis_perizinan_id}";
        $nomor_jenis = $this->pengaturanService->findByNamaAndGroup($nomor_jenis_format, 'izin');
        if (!$nomor_jenis) return;
        if (!$nomor_terakhir_jenis_perizinan)
            $nomor_terakhir_jenis_perizinan = (int) $nomor_jenis->value + 1;

        $this->pengaturanService->updateOrCreate($nomor_jenis_format, $nomor_terakhir_jenis_perizinan, 'izin');
    }

    public function generateDefaultFormatSurat(): void
    {
        $formatSurat = '{nomor}/{instansi}/{jenis-izin}/{no-jenis-izin}/{bulan-romawi}/{tahun}';
        $this->pengaturanService->updateOrCreate('nomor-surat', 0, 'surat');
        $this->pengaturanService->updateOrCreate('instansi-surat', 'DPMPTSP-BB', 'surat');
        $this->pengaturanService->updateOrCreate('format-surat', $formatSurat, 'surat');

        $bagianFormatSurat = [
            'nomor' => '{nomor}',
            'instansi' => '{instansi}',
            'jenis-izin' => '{jenis-izin}',
            'no-jenis-izin' => '{no-jenis-izin}',
            'bulan-romawi' => [
                '{bulan-romawi}',
                '{bulan-angka}',
                '{bulan-nama}'
            ],
            'tahun' => '{tahun}'
        ];
        $this->pengaturanService->updateOrCreate('bagian-format-surat', $bagianFormatSurat, 'surat');
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
                'group' => 'izin',
                'nama' => "JENIS-{$jenisPerizinan->id}",
                'value' => $jenis_format
            ];
            $setupdatajenisnomor[] = [
                'group' => 'izin',
                'nama' => "NO-JENIS-{$jenisPerizinan->id}",
                'value' => 0
            ];
        }
        $this->pengaturanService->createMany($setupdatajenis);
        $this->pengaturanService->createMany($setupdatajenisnomor);
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