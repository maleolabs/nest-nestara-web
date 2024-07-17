<?php

namespace App\Livewire\User\Izin;

use App\Services\AppService\FileService;
use App\Services\DomainServices\DokumenPemohonService;
use App\Services\DomainServices\PerizinanService;
use App\Services\DomainServices\SyaratIzinService;
use App\utils\enums\StatusIzinProgressEnum;
use Illuminate\Validation\ValidationException;
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
        $persyaratan = [],
        $persyaratan_tmp = [],
        $persyarata_actual_tmp = [],
        $keterangan;

    public function mount()
    {
        if ($this->izin_id) {
            $this->izin = $this->getPerizinanService()->find($this->izin_id);
            $this->status = $this->izin->status_izin->last();
            if ($this->status->progres == StatusIzinProgressEnum::FINISHED)
                $this->keterangan = $this->status->keterangan;
        }
        $this->setUpData();
        $this->syarat_tipe_pdf = $this->getSyaratIzinService()->getIdsTypePdfByJenisAndPemohon($this->izin->jenis_perizinan_id, $this->izin->pemohon_id);
        foreach ($this->syarat_izin as $key => $syarat) {
            $syarat_dokumen = $syarat->dokumen_pemohon->first();
            $nama_file = $syarat_dokumen->nama_file_alternatif ?? $syarat_dokumen->nama_file;
            $this->persyaratan_tmp[$key] = route('render-file', ['file_name' => base64_encode($nama_file)]);
        }
    }

    public function updatedPersyaratan($value, $key)
    {
        $syarat_mime = $this->syarat_izin[$key]->tipe;
        $mimes = ($syarat_mime =='img' || $syarat_mime ==  'image') ? 'mimes:jpg,png,jpeg' : 'mimes:pdf';
        $this->validate([
            "persyaratan.{$key}" => ['required',$mimes,'max:2048']
        ]);
        if (in_array($key, $this->syarat_tipe_pdf))
            $this->dispatch('start-loading', ['id' => $key]);
        else
            $this->persyaratan_tmp[$key] = $this->getCleanTmpPath($value);
    }
    
    public function render()
    {
        $this->setUpData();
        return view('livewire.user.izin.revisi-izin');
    }

    private function setUpData()
    {
        if (!$this->izin)
            return redirect()->route('user.izin');
        $this->syarat_izin = $this->getSyaratIzinService()->getByJenisAndPemohon($this->izin->jenis_perizinan_id, $this->izin->pemohon_id);
    }

    public function save()
    {
        if (count($this->persyaratan) > 0)
            $this->getDokumenPemohonService()->saveAllWithFile($this->izin->pemohon_id, $this->izin->id, $this->persyaratan, $this->persyarata_actual_tmp);

        $this->getPerizinanService()->saveRegistrationRevision($this->izin_id, $this->keterangan);

        return redirect()->route('user.izin');
    }

    #[On('set-pdf-img')]
    public function setPdfImg($id)
    {
        $pdfTempUrl = $this->getCleanTmpPath($this->persyaratan[$id]);
        if ($pdfTempUrl) {
            $this->dispatch('set-preview-img-modal', [
                'type' => 'pdf',
                'path' => $pdfTempUrl,
                'pemohon_id' => $this->izin->pemohon_id,
                'izin_id' => $this->izin->id,
                'syarat_id' => $id,
                'temp' => true
            ]);
        }
    }

    #[On('temp-img-uploaded')]
    public function tempImgUploaded($data)
    {
        $uploaded_temp_id = $data['syarat_id'];
        $uploaded_temp_img = $data['temp_file_path'];
        $this->persyarata_actual_tmp[$uploaded_temp_id] = $uploaded_temp_img;
        $this->persyaratan_tmp[$uploaded_temp_id] = route('render-file', ['file_name' => base64_encode($uploaded_temp_img)]);
        $this->dispatch('stop-loading', ['id' => $uploaded_temp_id]);
    }

    #[On('set-error')]
    public function setErrorWhileRenderPdf($data)
    {
        $this->dispatch('stop-loading', ['id' => $data['syarat_id']]);
        throw ValidationException::withMessages([
            'persyaratan.'. $data['syarat_id'] => $data['message']
        ]);
    }

    private function getCleanTmpPath($lvw_temp_file): string
    {
        $path = $this->getLivewireTmp($lvw_temp_file);
        $base64TmpPath = $this->encode_file_path($path);
        return route('render-file', ['file_name' => $base64TmpPath]);
    }

    private function encode_file_path($path): string
    {
        return base64_encode($path);
    }

    private function getLivewireTmp($lvw_temp_file): string
    {
        $lvwTmpUrl = explode('/', $lvw_temp_file->temporaryUrl());
        $lvwTmpUrl = explode('?', end($lvwTmpUrl));
        return 'livewire-tmp/' . $lvwTmpUrl[0];
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

    private function getDokumenPemohonService(): DokumenPemohonService
    {
        return app(DokumenPemohonService::class);
    }

    private function getFileService(): FileService
    {
        return app(FileService::class);
    }
    
}