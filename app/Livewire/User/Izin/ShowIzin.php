<?php

namespace App\Livewire\User\Izin;

use App\Services\DomainServices\JenisPerizinanService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowIzin extends Component
{

    public $selected_preview_id;

    private $semua_izin = [];

    public $to_confirm_delete = false,
        $delete_izin_id,
        $confirm_delete,
        $to_confirm_cancel = false,
        $cancel_izin_id,
        $cancel_feedback;

    public function render()
    {
        $this->semua_izin = auth()->user()->pemohon
            ? auth()->user()->pemohon->izin()->with(['jenis_perizinan', 'latestStatus'])->paginate(10)
            : new Collection();

        $selectedPreview = $this->selected_preview_id
            ? $this->getPerizinanService()->find($this->selected_preview_id)
            : null;
        $selectedPreviewSyaratIzin = $selectedPreview && auth()->user()->pemohon
            ? $this->getSyaratIzinService()->getByJenisAndPemohon($selectedPreview->jenis_perizinan_id, auth()->user()->pemohon->id)
            : [];

        return view('livewire.user.izin.show-izin', [
            'semua_izin' => $this->semua_izin,
            'selectedPreview' => $selectedPreview,
            'selectedPreviewSyaratIzin' => $selectedPreviewSyaratIzin,
        ]);
    }

    public function isIzinCancelable(string $izin_status)
    {
        return in_array($izin_status, $this->getPerizinanService()->getAllStatusCancelable());
    }

    public function preview(int $id)
    {
        $preview = $this->getPerizinanService()->find($id);

        if (! $preview || ! auth()->user()->pemohon || $preview->pemohon_id !== auth()->user()->pemohon->id) {
            return;
        }

        $this->selected_preview_id = $preview->id;
        $this->dispatch('izin-preview-opened');
    }

    public function closePreview()
    {
        $this->selected_preview_id = null;
        $this->dispatch('izin-preview-closed');
    }

    public function cancel(int $id)
    {
        $izin = $this->getPerizinanService()->find($id);
        if (! $izin || ! auth()->user()->pemohon || $izin->pemohon_id !== auth()->user()->pemohon->id) {
            $this->cancel_feedback = 'Permohonan tidak ditemukan atau bukan milik Anda.';
            return;
        }

        $this->cancel_izin_id = $id;
        $this->cancel_feedback = null;
        $this->to_confirm_cancel = true;
    }

    public function confirmCancel()
    {
        $izin = $this->cancel_izin_id ? $this->getPerizinanService()->find($this->cancel_izin_id) : null;
        if (! $izin || ! auth()->user()->pemohon || $izin->pemohon_id !== auth()->user()->pemohon->id) {
            $this->cancel_feedback = 'Permohonan tidak ditemukan atau bukan milik Anda.';
            return;
        }

        $cancelled = $this->getPerizinanService()->cancelRegistration($izin->id);
        $this->cancel_feedback = $cancelled
            ? 'Permohonan berhasil dibatalkan.'
            : 'Permohonan tidak dapat dibatalkan karena status sudah berubah.';
        $this->cancel_izin_id = null;
        $this->to_confirm_cancel = false;
    }

    public function dismissCancel()
    {
        $this->cancel_izin_id = null;
        $this->to_confirm_cancel = false;
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
