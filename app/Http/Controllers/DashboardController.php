<?php

namespace App\Http\Controllers;

use App\Services\AppService\EncryptionService;
use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use App\utils\enums\StatusIzinEnum;
use App\utils\enums\StatusIzinProgressEnum;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    protected PerizinanService $perizinanService;

    protected EncryptionService $encryptionService;

    public function __construct(
        PerizinanService $perizinanService,
        EncryptionService $encryptionService)
    {
        $this->perizinanService = $perizinanService;
        $this->encryptionService = $encryptionService;
    }
    
    public function previewIzin(Request $request, $izin_id)
    {
        try {
            $izin_id = (int) $this->encryptionService->decryptId($izin_id);
        } catch (DecryptException $e) {
            abort(500, $e->getMessage());
        }
        $izin = $this->perizinanService->find($izin_id);
        if (!$izin)
            return redirect()->route('dashboard');
        return response()->view('dashboard.preview-izin', [
            'izin' => $izin
        ]);
    }

    public function confirmIzin(Request $request)
    {
        $izin = null;
        $message = null;
        $izin_id = $request->izin_id;
        try {
            $izin_id = $this->encryptionService->decryptId($izin_id);
            $izin = $this->perizinanService->find($izin_id);
        } catch (DecryptException $e) {
            $message = 'Izin tidak terdaftar';
        }

        return response()->view('confirm-izin', compact('izin', 'message'));
    }

    public function getCountByProcess($process)
    {
        $status = $this->getProcessStatus($process);
        $progress = $this->getProcessProgress($process);
        $keterangan = $this->getProcessKeterangan($process);
        
        return $this->perizinanService->getCount($status, $progress, $keterangan);
    }

    private function getProcessStatus($process): array
    {
        $statusMap = [
            'pendaftaran' => [StatusIzinEnum::DIDAFTARKAN, StatusIzinEnum::DITINJAU, StatusIzinEnum::REVISI_PERSYARATAN],
            'pendataan' => [StatusIzinEnum::PROSES],
            'rekomendasi' => [StatusIzinEnum::PROSES],
            'penetapan' => [StatusIzinEnum::PROSES],
            'penyerahan' => [StatusIzinEnum::PENERBITAN, StatusIzinEnum::PENYERAHAN],
        ];

        return $statusMap[$process] ?? [];
    }

    private function getProcessProgress($process): array
    {
        $defaultProgress = [
            StatusIzinProgressEnum::UNPROCESSED,
            StatusIzinProgressEnum::PROCESSING,
            StatusIzinProgressEnum::FINISHED
        ];

        $progressMap = [
            'pendaftaran' => $defaultProgress,
            'pendataan' => $defaultProgress,
            'rekomendasi' => $defaultProgress,
            'penetapan' => $defaultProgress,
            'penyerahan' => $defaultProgress,
        ];

        return $progressMap[$process] ?? [];
    }

    private function getProcessKeterangan($process): array
    {
        $keteranganMap = [
            'pendaftaran' => [],
            'pendataan' => [
                BackOfficeStatusEnum::BELUM_ENTRI,
                BackOfficeStatusEnum::SEDANG_ENTRI,
                BackOfficeStatusEnum::SUDAH_ENTRI,
                BackOfficeStatusEnum::ENTRI_TINJAUAN_LAPANGAN,
                BackOfficeStatusEnum::SUDAH_ENTRI_TINJAUAN_LAPANGAN,
            ],
            'rekomendasi' => [
                BackOfficeStatusEnum::TIDAK_DIREKOM,
                BackOfficeStatusEnum::DIREKOM,
                BackOfficeStatusEnum::REKOM_CATATAN,
                BackOfficeStatusEnum::BAHAS_LANJUT
            ],
            'penetapan' => [
                BackOfficeStatusEnum::DIREKOM,
                BackOfficeStatusEnum::REKOM_CATATAN,
                BackOfficeStatusEnum::DIIZINKAN,
                BackOfficeStatusEnum::TIDAK_DIIZINKAN,
                BackOfficeStatusEnum::LAYANAN_DITOLAK
            ]
        ];

        return $keteranganMap[$process] ?? [];
    }
    
}