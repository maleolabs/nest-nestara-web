<?php

namespace App\Livewire\Dashboard;

use App\Services\AppService\FileService;
use App\Services\DomainServices\DokumenPemohonService;
use Livewire\Attributes\On;
use Livewire\Component;

class DashboardUtil extends Component
{
    public function render()
    {
        return view('livewire.dashboard.dashboard-util');
    }

    #[On('save-preview-img')]
    public function savePreviewImg($image_data, $pemohon_id, $syarat_id, $izin_id)
    {
        $image_path = $this->getFileService()->saveBase64Img($image_data, 'dokumen_persyaratan');
        $obj = $this->getDokumenPemohonService()->saveFileAlternate($pemohon_id, $syarat_id, $izin_id, $image_path);
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