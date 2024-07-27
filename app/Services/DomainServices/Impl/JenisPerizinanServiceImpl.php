<?php

namespace App\Services\DomainServices\Impl;

use App\Models\JenisPerizinan;
use App\Services\AppService\FileService;
use App\Services\AppService\PdfTemplateService;
use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PengaturanService;
use App\utils\enums\SuratGroupsEnum;
use Illuminate\Pagination\LengthAwarePaginator;

class JenisPerizinanServiceImpl implements JenisPerizinanService 
{

    protected PdfTemplateService $pdfTemplateService;

    protected PengaturanService $pengaturanService;

    protected FileService $fileService;

    public function __construct(
        PdfTemplateService $pdfTemplateService,
        PengaturanService $pengaturanService,
        FileService $fileService
    ) {
        $this->pdfTemplateService = $pdfTemplateService;
        $this->pengaturanService = $pengaturanService;
        $this->fileService = $fileService;
    }

    public function save(array $data): ?JenisPerizinan
    {
        $jenis_perizinan = JenisPerizinan::create($data);

        $jenis_stand = '';
            $jenis_words = explode(' ', $jenis_perizinan->nama);
            if (count($jenis_words) > 2) {
                foreach ($jenis_words as $jenis_word) {
                    $first_letter = substr($jenis_word, 0, 1);
                    if (ctype_alpha($first_letter)) $jenis_stand .= strtoupper($first_letter);
                }
            } else
                $jenis_stand = strtoupper(str_replace(' ', '-', $jenis_perizinan->nama));

        $jenis_perizinan->template_folder = $this->pdfTemplateService->generatePdfTemplateFile($jenis_stand);
        $jenis_perizinan->save();

        return $jenis_perizinan;
    }

    public function saveField(int $id, string $field, string $value): ?JenisPerizinan
    {
        $perizinan = JenisPerizinan::find($id);
        if (!$perizinan) return null;

        $perizinan->update([$field => $value]);

        return JenisPerizinan::find($id);
    }

    public function find(int $id): ?JenisPerizinan
    {
        return JenisPerizinan::with('syarat_izin')->where('id', $id)->first();
    }

    public function findByName(string $name): ?JenisPerizinan
    {
        return JenisPerizinan::with('syarat_izin')->where('nama', $name)->first();
    }

    public function getPdfTemplate(int $id): ?string
    {
        $jenis_perizinan = JenisPerizinan::find($id);
        if (!$jenis_perizinan)
            return null;

        $savedTemplate = $this->pengaturanService->findByNamaAndGroup("TEMPLATE-SURAT-{$id}", SuratGroupsEnum::SURAT_TEMPLATE_GROUP);

        if (!$savedTemplate)
            return null;

        $blade_template = str_replace('.blade.php', '', $savedTemplate->value);
        $clean_view_template = str_replace("\\", '.', $blade_template);

        return "{$jenis_perizinan->template_folder}.{$clean_view_template}";
    }

    public function getAll(string $sorting_by = 'created_at', string $sort = 'DESC'): array
    {
        $jenis_perizinan = JenisPerizinan::orderBy($sorting_by, $sort)->get();
        return $jenis_perizinan->all();
    }

    public function getPaginateAll(int $page = 1, int $count = 10, string $sorting_by = 'created_at', $sort = 'DESC'): LengthAwarePaginator
    {
        return JenisPerizinan::orderBy($sorting_by, $sort)
                ->paginate($count, ['*'], 'page', $page);
    }

    public function searchByName(string $nama, ?int $limit = null): array
    {
        $perizinan = JenisPerizinan::select('id', 'nama')->where('nama', 'like', '%' . $nama . '%');
        if ($limit)
            $perizinan = $perizinan->limit($limit);
        $perizinan = $perizinan->get();
        return $perizinan->all();
    }

    public function getPersyaratan(int $jenis_id): array
    {
        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan)
            return [];
        $persyaratan = $perizinan->syarat_izin()->get();
        $result = [];
        foreach ($persyaratan as $syarat) {
            $result[$syarat->id] = $syarat->nama;
        }
        return $result;
    }

    public function getPersyaratanByJenisAndPemohon(int $jenis_id, int $pemohon_id): array
    {
        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan)
            return [];
        return $perizinan->syarat_izin()->with('dokumen_pemohon', function ($dokumen_pemohon) use ($pemohon_id) {
            $dokumen_pemohon->where('pemohon_id', $pemohon_id);
        })->get();
    }

    public function getAllTemplateById(int $jenis_id): array
    {
        $templates = [];

        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan) return $templates;

        $template_folder = $perizinan->template_folder;
        $templates = $this->fileService->fileManager()
                    ->getFile()
                    ->folders($template_folder)
                    ->getResourceFile()
                    ->getFilesName();
        return $templates;
    }

    public function canUseTemplateById(int $jenis_id): bool
    {
        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan) return false;

        $savedTemplate = $this->pengaturanService->findByNamaAndGroup("TEMPLATE-SURAT-{$jenis_id}", 'surat-template');
        if (!$savedTemplate) return false;

        return $this->fileService->fileManager()
                ->getFile()
                ->folders($perizinan->template_folder)
                ->getResourceFile()
                ->isFileNotEmpty($savedTemplate->value);
    }

    public function getIdsWhereTemplateExists(): array
    {
        $ids = [];

        $perizinan = JenisPerizinan::select('id', 'template_folder')->pluck('template_folder', 'id');

        foreach ($perizinan as $id => $folder) {
            $savedTemplate = $this->pengaturanService->findByNamaAndGroup("TEMPLATE-SURAT-{$id}", 'surat-template');

            if (!$savedTemplate) continue;

            $file = $this->fileService->fileManager()
                    ->getFile()
                    ->folders($folder)
                    ->getResourceFile()
                    ->isFileNotEmpty($savedTemplate->value);

            if ($file)
                $ids[] = $id;
        }

        return $ids;
    }

    public function getJenisFormatSuratById(int $jenis_id): ?string
    {
        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan) return null;

        $nama_format = "JENIS-{$jenis_id}";
        $format_surat = $this->pengaturanService->findByNamaAndGroup($nama_format, SuratGroupsEnum::JENIS_SURAT_GROUP);
        return $format_surat->value ?? null;
    }

    public function getNomorSuratById(int $jenis_id): ?int
    {
        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan) return null;

        $nama_nomor_surat = "NO-JENIS-{$jenis_id}";
        $nomor_jenis_surat = $this->pengaturanService->findByNamaAndGroup($nama_nomor_surat, SuratGroupsEnum::JENIS_SURAT_GROUP);
        return $nomor_jenis_surat->value ?? null;
    }

    public function getCurrentTemplateById(int $jenis_id): ?string
    {
        $perizinan = JenisPerizinan::find($jenis_id);
        if (!$perizinan) return null;

        $nama_template = "TEMPLATE-SURAT-{$jenis_id}";
        $selected_template = $this->pengaturanService->findByNamaAndGroup($nama_template, SuratGroupsEnum::SURAT_TEMPLATE_GROUP);
        return $selected_template->value ?? null;
    }

    public function hasEntriDataForm(?int $jenis_perizinan_id = null): bool
    {
        if (!$jenis_perizinan_id)
            return false;
        $jenis_perizinan = $this->find($jenis_perizinan_id);
        if (!$jenis_perizinan)
            return false;

        return $jenis_perizinan->entri_data()->count() > 0;
    }

}