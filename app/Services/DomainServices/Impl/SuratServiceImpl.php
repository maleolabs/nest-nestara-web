<?php

namespace App\Services\DomainServices\Impl;

use App\Services\DomainServices\Helpers\PengaturanSuratGenerator;
use App\Services\DomainServices\SuratService;
use App\utils\enums\SuratGroupsEnum;
use Carbon\Carbon;

class SuratServiceImpl extends PengaturanSuratGenerator implements SuratService {

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

    public function getLastNomor(): int
    {
        $nomor_surat = $this->pengaturanService->find('nomor-surat');
        
        return $nomor_surat ? $nomor_surat->value : 0;
    }

    public function getNomor(): int
    {
        $nomor_surat = $this->pengaturanService->find('nomor-surat');
        if (!$nomor_surat) return 1;
        return (int) $nomor_surat->value + 1;
    }

    public function saveNomor(int $nomor_terakhir): void
    {
        $this->pengaturanService->updateOrCreate('nomor-surat', $nomor_terakhir, SuratGroupsEnum::FORMAT_SURAT_GROUP);
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
        $tempat_penetapan = $this->pengaturanService->findByNamaAndGroup('tempat-penetapan', SuratGroupsEnum::KOP_SURAT_GROUP);

        if (!$logo_path || !$nama_pemerintah || !$nama_dinas || !$alamat || !$website || !$tempat_penetapan) {
            $this->generateDefaultKopSurat();
            return $this->getKopSurat();
        }

        $kop_surat['logo_path'] = $logo_path->value;
        $kop_surat['nama_pemerintah'] = $nama_pemerintah->value;
        $kop_surat['nama_dinas'] = $nama_dinas->value;
        $kop_surat['alamat'] = $alamat->value;
        $kop_surat['website'] = $website->value;
        $kop_surat['tempat_penetapan'] = $tempat_penetapan->value;

        return $kop_surat;
    }

    public function saveKopSurat(array $kop_data): void
    {
        $this->pengaturanService->updateOrCreate('nama-pemerintah', $kop_data['nama_pemerintah'], SuratGroupsEnum::KOP_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('nama-dinas', $kop_data['nama_dinas'], SuratGroupsEnum::KOP_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('alamat', $kop_data['alamat'], SuratGroupsEnum::KOP_SURAT_GROUP);
        $this->pengaturanService->updateOrCreate('website', $kop_data['website'], SuratGroupsEnum::KOP_SURAT_GROUP);
    }

    public function saveTempatPenetapan(string $tempat_penetapan): void
    {
        $this->pengaturanService->updateOrCreate('tempat-penetapan', $tempat_penetapan, SuratGroupsEnum::KOP_SURAT_GROUP);
    }

    public function getSuratContentByJenis(int $jenis_perizinan_id): array
    {
        $nama = "KONTEN-SURAT-{$jenis_perizinan_id}";
        $content = $this->pengaturanService->findByNamaAndGroup($nama, SuratGroupsEnum::KONTEN_SURAT_GROUP);
        return $content ? $content->value : [];
    }

}