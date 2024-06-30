<div>
    <md-linear-progress wire:loading wire:target='back' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <button class="btn btn-white rounded-pill z-1" wire:click='back'>
        <i class="fa-solid fa-arrow-left me-1"></i>
        Kembali
    </button>

    @if ($preview_izin)
        <div class="my-4 d-flex gap-3">
            <img src="{{ asset('storage/users/profile.png') }}" class="img-fluid img-rounded">
            <div>
                <h6 class="mb-0">{{ str_capitalize($preview_izin->pemohon->nama_pemohon, true) }}</h6>
                <p class="mb-0 text-sm">
                    <i class="fa-regular fa-id-card me-1"></i>
                    {{ $preview_izin->pemohon->nomor_id }}
                    <span class="text-uppercase">{{ "({$preview_izin->pemohon->sumber_identitas})" }}</span>
                </p> 
                <p class="mb-0 text-sm">
                    <i class="fa-solid fa-phone me-1"></i>
                    {{ $preview_izin->pemohon->no_telepon }}
                </p> 
                <div class="d-flex gap-2">
                    <i class="fa-solid fa-location-dot mt-1"></i>
                    <p class="text-capitalize text-sm">{{ $this->getAlamat($preview_izin->pemohon->id) }}</p>
                </div>
            </div>
        </div>
        <p class="mb-0 text-sm">Jenis perizinan</p>
        <h3 class="spacing-md">{{ str_capitalize($preview_izin->jenis_perizinan->nama, true) }}</h3>
        <p class="mb-0 text-sm">Kelompok Izin</p>
        <h6>Izin tanpa retribusi</h6>
        <h6 class="mb-0">
            <i class="fa-solid fa-hourglass-half me-1"></i>
            Durasi pengerjaan 1 hari
        </h6>
        <h6 class="mb-0">
            <i class="fa-regular fa-calendar-check me-1"></i>
            Masa berlaku izin 5 tahun
        </h6>
        <p class="mt-3 mb-0 text-muted">Status izin</p>
        <div class="d-flex align-items-top gap-2">
            <i class="fa-regular fa-circle-check mt-1 text-success"></i>
            <div>
                <p class="mb-0 text-success">Telah didaftarkan</p>
                <p class="mb-0">{{ $preview_izin->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>
        <p class="mt-3 mb-0 text-muted">Persyaratan</p>
        <div class="rounded-3 border border-danger bg-danger-light p-3 my-3 text-danger">
            <strong>Penting:</strong>
            <p class="mb-0">
                Dokumen ini bersifat sangat sensitif dan rahasia. Pastikan untuk tidak menyimpan atau mengunduhnya di tempat yang tidak aman atau diakses oleh pihak yang tidak berwenang.
            </p>
        </div>
        @foreach ($preview_izin_syarat_izin as $syarat)
            <h6 @if(!isset($syarat->dokumen_pemohon[0]))class="text-muted text-decoration-line-through"@endif>
                @if (isset($syarat->dokumen_pemohon[0]))
                    <a href="{{ route('download-file', ['file_name' => $syarat->dokumen_pemohon[0]->nama_file]) }}" class="text-decoration-none d-inline">
                        <i class="fa-solid fa-download me-1"></i>
                    </a>
                    <a href="javascript:;" class="text-decoration-none d-inline" wire:click="showImgModal('{{ $syarat->nama }}', '{{ $syarat->dokumen_pemohon[0]->nama_file }}')">
                        <i class="fa-solid fa-eye me-1"></i>
                    </a>
                    <button type="button" id="btnShowPreviewModal" data-bs-toggle="modal" data-bs-target="#showDocumentImgModal" class="opacity-0"></button>
                @else
                    <i class="fa-solid fa-download me-1"></i>
                     <i class="fa-solid fa-eye me-1"></i>
                @endif
                {{ $syarat->nama }}
            </h6>
        @endforeach
        <div class="modal fade" id="showDocumentImgModal" tabindex="-1" aria-labelledby="showDocumentImgModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="showDocumentImgModalLabel">{{ $modalImgTitle }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($modalImgSource)
                            <img src="{{ route('render-file', ['file_name' => $modalImgSource]) }}" class="img-fluid">
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>