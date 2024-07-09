<?php

namespace App\Livewire\Dashboard;

use App\Services\AppService\FileService;
use App\Services\DomainServices\DokumenPemohonService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class DashboardUtil extends Component
{
    public function render()
    {
        return view('livewire.dashboard.dashboard-util');
    }

    #[On('save-preview-img')]
    public function savePreviewImg($image_data, $pemohon_id, $syarat_id, $izin_id, $temp = false)
    {
        $image_path = '';
        if ($temp) {
            $image_path = $this->getFileService()->saveBase64Img($image_data, 'dokumen_persyaratan', 'temp_files')->getPath();
            $this->dispatch('temp-img-uploaded', [
                'temp_file_path' => $image_path,
                'syarat_id' => $syarat_id
            ]);
        } else {
            $existing_dokumen = $this->getDokumenPemohonService()->findByPemohonAndSyaratAndIzin($pemohon_id, $syarat_id, $izin_id);
            $image_path = $this->getFileService()->saveBase64Img($image_data, 'dokumen_persyaratan')->deleteExisting($existing_dokumen->nama_file_alternatif)->getPath();
            $this->getDokumenPemohonService()->saveFileAlternates($pemohon_id, $syarat_id, $izin_id, $image_path);
        }
        Log::channel('app')->info('menyimpan pdf img: ' . $image_path);
        Log::channel('app')->info('pdf img syarat id: ' . $syarat_id);
    }

    private function getFileService(): FileService
    {
        return app(FileService::class);
    }

    private function getDokumenPemohonService(): DokumenPemohonService
    {
        return app(DokumenPemohonService::class);
    }
    
}