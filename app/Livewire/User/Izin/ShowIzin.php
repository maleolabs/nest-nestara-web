<?php

namespace App\Livewire\User\Izin;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Livewire\Component;

class ShowIzin extends Component
{

    public $selectedPreview, 
        $selectedPreviewSyaratIzin = [];

    public $to_confirm_delete = false,
        $delete_izin_id,
        $confirm_delete;

    public function render()
    {
        return view('livewire.user.izin.show-izin', [
            'semua_izin' => auth()->user()->pemohon->izin()->paginate(10)
        ]);
    }

    public function isIzinCancelable(string $izin_status)
    {
        return in_array($izin_status, $this->getPerizinanService()->getAllStatusCancelable());
    }

    public function preview(int $id)
    {
        $this->selectedPreview = $this->getPerizinanService()->find($id);
        $this->selectedPreviewSyaratIzin = $this->getSyaratIzinService()->getByJenisAndPemohon($this->selectedPreview->jenis_perizinan_id, auth()->user()->pemohon->id);
    }

    public function closePreview()
    {
        $this->selectedPreview = null;
        $this->selectedPreviewSyaratIzin = [];
    }

    public function cancel(int $id)
    {
        $this->getPerizinanService()->cancelRegistration($id);
    }

    public function delete(int $id)
    {
        $this->closePreview();
        $this->to_confirm_delete = true;
        $this->delete_izin_id = $id;
    }

    public function confirmDelete()
    {
        $this->getPerizinanService()->delete($this->delete_izin_id);
        $this->confirm_delete = false;
        $this->delete_izin_id = null;
        $this->to_confirm_delete = false;
    }

    public function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }

    public function getJenisPerizinanService(): JenisPerizinanService
    {
        return app(JenisPerizinanService::class);
    }

    public function getSyaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }
    
}