<?php

namespace App\Livewire\User\Izin;

use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class RevisiIzin extends Component
{

    use WithFileUploads;

    public $izin_id,
        $izin,
        $status,
        $syarat_izin = [],
        $syarat_tipe_pdf = [],
        $persyaratan = [];

    public function mount()
    {
        if ($this->izin_id) {
            $this->izin = $this->getPerizinanService()->find($this->izin_id);
            $this->status = $this->izin->status_izin->last();
        }
    }

    public function updatedPersyaratan($value, $key)
    {
        if (in_array($key, $this->syarat_tipe_pdf))
            $this->dispatch('start-loading', ['id' => $key]);
    }
    
    public function render()
    {
        $this->setUpData();
        return view('livewire.user.izin.revisi-izin');
    }

    private function setUpData()
    {
        if (!$this->izin)
            return;
        $this->syarat_izin = $this->getSyaratIzinService()->getByJenisAndPemohon($this->izin->jenis_perizinan_id, $this->izin->pemohon_id);
        $this->syarat_tipe_pdf = $this->getSyaratIzinService()->getIdsTypePdfByJenisAndPemohon($this->izin->jenis_perizinan_id, $this->izin->pemohon_id);
    }

    #[On('set-pdf-img')]
    public function setPdfImg($id)
    {
        $pdfTempUrl = $this->persyaratan[$id];
        if ($pdfTempUrl) {
            $lvwTmpUrl = explode('/', $pdfTempUrl->temporaryUrl());
            $lvwFileName = explode('?', $lvwTmpUrl[5]);
            $pdfTempUrl = base64_encode('livewire-tmp/' . $lvwFileName[0]);
            $pdfTempUrl = route('render-file', ['file_name' => $pdfTempUrl]);
        }
        $this->dispatch('set-preview-img-modal', [
            'type' => 'pdf',
            'path' => $pdfTempUrl,
            'pemohon_id' => $this->izin->pemohon_id,
            'izin_id' => $this->izin->id,
            'syarat_id' => $id,
            'temp' => true
        ]);
    }

    #[On('temp-img-uploaded')]
    public function tempImgUploaded($data)
    {
        $this->persyaratan[$data['syarat_id']] = route('render-file', ['file_name' => base64_encode($data['temp_file_path'])]);
        $this->dispatch('stop-loading', ['id' => $data['syarat_id']]);
    }

    protected function canAccess(): bool
    {
        return $this->izin != null;
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    private function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }
    
}