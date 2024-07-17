<?php

namespace App\Livewire\Dashboard\BackOffice\Partials;

use App\Services\DomainServices\PerizinanService;
use App\utils\enums\BackOfficeStatusEnum;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;

class RekomForm extends Component
{

    #[Url()]
    public $preview_izin_id;

    public $preview_izin;

    public $rekomendasi,
        $jenis_kegiatan;

    public $saved = false;

    public function mount()
    {
        if ($this->preview_izin_id) $this->preview_izin = $this->getPerizinanService()->find($this->preview_izin_id);

        if ($this->preview_izin) {
            $pemohon = str_capitalize($this->preview_izin->pemohon->nama_pemohon, true);
            $jp = Str::limit(str_capitalize($this->preview_izin->jenis_perizinan->nama, true), 30);
            $this->dispatch('preview-selected', ['preview' => "{$pemohon} ({$jp})"]);
        }
    }

    public function save()
    {
        $this->validateForm();
        $auth_user = auth()->user();
        $pegawai_id = $auth_user->pegawai ? $auth_user->pegawai->id : null;
        $this->getPerizinanService()->rekomendasiPermohonan($this->preview_izin_id, $pegawai_id, $this->rekomendasi, $this->jenis_kegiatan);
        $this->saved = true;
    }

    private function validateForm()
    {
        $rekom_status = [
            BackOfficeStatusEnum::DIREKOM,
            BackOfficeStatusEnum::TIDAK_DIREKOM,
            BackOfficeStatusEnum::REKOM_CATATAN,
            BackOfficeStatusEnum::BAHAS_LANJUT,
        ];
        $jk_status = [
            BackOfficeStatusEnum::PERIKSA_BERKAS,
            BackOfficeStatusEnum::SURVEY_LAPANGAN,
            BackOfficeStatusEnum::RAPAT_TIM
        ];
        $this->validate([
            'rekomendasi' => ['required', Rule::in($rekom_status)],
            'jenis_kegiatan' => ['required', Rule::in($jk_status)]
        ]);
    }

    public function cancel()
    {
        return redirect()->route('pegawai.rekomendasi');
    }

    public function render()
    {
        return view('livewire.dashboard.back-office.partials.rekom-form');
    }

    private function getPerizinanService(): PerizinanService
    {
        return app(PerizinanService::class);
    }
    
}