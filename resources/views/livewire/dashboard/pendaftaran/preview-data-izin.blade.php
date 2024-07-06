<div>
    <md-linear-progress wire:loading wire:target='back, saveAction' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="row px-2">
        @if ($preview_izin)
            <div class="col-md-3 d-flex align-items-stretch">
                <div class="card w-100 p-4">
                    <div class="d-flex flex-column gap-3">
                        <img src="{{ asset('storage/users/' . $preview_izin->pemohon->user->foto) }}" class="img-fluid img-rounded img-rounded-lg mx-auto">
                        <div class="px-2">
                            <h5 class="mb-2">{{ str_capitalize($preview_izin->pemohon->nama_pemohon, true) }}</h5>
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
                </div>
            </div>
            <div class="col-md-9 d-flex align-items-stretch">
                <div class="card w-100 p-4">
                    <p class="mb-0 text-sm">Jenis perizinan</p>
                    <h3 class="spacing-md">{{ str_capitalize($preview_izin->jenis_perizinan->nama, true) }}</h3>
                    <h6 class="mb-0 spacing-normal">
                        <i class="fa-solid fa-layer-group"></i>
                        Izin tanpa retribusi
                    </h6>
                    <h6 class="mb-0 spacing-normal">
                        <i class="fa-solid fa-hourglass-half mx-1"></i>
                        Durasi pengerjaan 1 hari
                    </h6>
                    <h6 class="mb-0 spacing-normal">
                        <i class="fa-regular fa-calendar-check mx-1"></i>
                        Masa berlaku izin 5 tahun
                    </h6>
                    <div class="mt-4">
                        <h6 class="text-bold">
                            <i class="fa-regular fa-circle-right me-1"></i>
                            Status Izin
                        </h6>
                        @foreach ($preview_izin->status_izin as $status)
                            <div class="d-flex align-items-top gap-2 mb-2 ps-4">
                                @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                    <i class="fa-regular fa-circle-check mt-1 text-success"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED)
                                    <i class="fa-solid fa-circle-minus mt-1 text-muted"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                    <i class="fa-solid fa-hourglass-half mt-1 text-dark"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                    <i class="fa-solid fa-circle-xmark mt-1 text-danger"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                    <i class="fa-solid fa-triangle-exclamation mt-1 text-warning"></i>
                                @endif
                                <div>
                                    <p class="mb-0 @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                            text-success
                                        @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED)
                                            text-muted
                                        @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                            text-dark
                                        @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                            text-danger
                                        @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                            text-warning
                                        @endif">
                                        {{ $status->nama != App\utils\enums\StatusIzinEnum::DIDAFTARKAN ? "{$status->nama} ({$status->progres})" : $status->nama }}
                                    </p>
                                    <p class="mb-0">{{ $status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA' }}</p>
                                    <small class="text-muted text-sm fst-italic">{{ $status->keterangan ?? '' }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link @if($active_tab != self::TINDAKAN)active @endif" data-bs-toggle="tab" data-bs-target="#persyaratan-tab">
                                    Persyaratan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link @if($active_tab == self::TINDAKAN)active @endif" data-bs-toggle="tab" data-bs-target="#tindakan-tab">
                                    Tindakan
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content border border-top-0 rounded-bottom-2 p-4">
                            <div class="tab-pane fade @if($active_tab != self::TINDAKAN)show active @endif" id="persyaratan-tab">
                                @if (count($preview_izin_syarat_izin) > 0)
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
                                                <a href="javascript:;" class="text-decoration-none d-inline" wire:click="showImgModal('{{ $syarat->nama }}', '{{ $syarat->dokumen_pemohon[0]->nama_file }}', '{{ $syarat->tipe }}')">
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
                                @else
                                    <p class="mt-3 text-muted">
                                        <i class="fa-solid fa-circle-info me-1"></i>
                                        Tidak ada persyaratan untuk perizinan ini!
                                    </p>
                                @endif
                            </div>
                            <div class="tab-pane fade @if($active_tab == self::TINDAKAN)show active @endif" id="tindakan-tab">
                                @if ($this->canProcess())
                                    <form wire:submit.prevent='saveAction'>
                                        <label for="tanggal_peninjauan" class="form-label">Tanggal Peninjauan</label>
                                        <div class="input-group input-group-outline mb-3">
                                            <input type="datetime" class="form-control disabled @error('tanggal_peninjauan')is-invalid @enderror" id="tanggal_peninjauan" disabled wire:model='tanggal_peninjauan'>
                                        </div>
                                        @error('tanggal_peninjauan')
                                            <div class="mb-3 text-danger text-sm">{{ $message }}</div>
                                        @enderror
                                        <label for="lokasi_izin" class="form-label">Lokasi Izin</label>
                                        <div class="input-group input-group-outline mb-3">
                                            <input type="text" class="form-control @error('lokasi_izin')is-invalid @enderror" id="lokasi_izin" wire:model='lokasi_izin'>
                                        </div>
                                        @error('lokasi_izin')
                                            <div class="mb-3 text-danger text-sm">{{ $message }}</div>
                                        @enderror
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <div class="input-group input-group-outline mb-3">
                                            <textarea rows="3" class="form-control @error('keterangan')is-invalid @enderror" id="keterangan" wire:model='keterangan'></textarea>
                                        </div>
                                        @error('keterangan')
                                            <div class="mb-3 text-danger text-sm">{{ $message }}</div>
                                        @enderror
                                        <div class="form-check ps-0 mb-3">
                                            <input type="checkbox" class="form-check-input" id="pendaftaran-selesai" wire:model='pendaftaran_selesai'>
                                            <label for="pendaftaran-selesai" class="form-check-label">Pendaftaran Selesai</label>
                                        </div>
                                        <div class="d-flex justify-content-start gap-3">
                                            <button class="btn bg-gradient-primary" type="submit">Simpan</button>
                                        </div>
                                    </form>
                                @else
                                    <small class="text-muted">Tanggal Peninjauan</small>
                                    <h6>{{ Carbon\Carbon::parse($preview_izin->tanggal_peninjauan)->translatedFormat('d F Y H:i') . ' WITA' }}</h6>
                                    <small class="text-muted">Lokasi Izin</small>
                                    <h6>{{ $preview_izin->lokasi_izin }}</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="modal fade" id="showDocumentImgModal" tabindex="-1" aria-labelledby="showDocumentImgModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h1 class="modal-title fs-5" id="showDocumentImgModalLabel">{{ $modalImgTitle }}</h1>
                    @if ($modalPdfSource)
                        <p class="mb-0 text-muted">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            PDF ini dalam mode <span class="fst-italic">read only</span>.
                        </p>
                    @endif
                </div>
                <div class="modal-body">
                    @if ($modalImgSource)
                        <img src="{{ route('render-file', ['file_name' => $modalImgSource]) }}" class="img-fluid">
                    @elseif($modalPdfSource)
                        <div class="d-flex justify-content-center">
                            <div id="pdf-container"></div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close-modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>