<div>
    <md-linear-progress wire:loading wire:target='back, saveAction, saveEntri' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="row px-2">
        @if ($preview_izin)
            <div class="col-md-3 d-flex align-items-stretch mb-4">
                <div class="card w-100 p-4 mb-0">
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
            <div class="col-md-9 d-flex align-items-stretch mb-4">
                <div class="card w-100 p-4 mb-0">
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
                                    <p class="mb-0 text-sm">{{ $status->created_at->translatedFormat('l, d F Y H:i') . ' WITA.' }}</p>
                                    @if ($status->created_at != $status->updated_at)
                                        <p class="mb-0 text-sm">
                                            <i class="fa-solid fa-rotate me-1"></i>
                                            {{ 'Terakhir diupdate ' .$status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA' }}
                                        </p>
                                    @endif
                                    @if ($status->keterangan)
                                        <small class="text-muted text-sm">{{ 'Keterangan: ' . $status->keterangan ?? '' }}</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @role('pegawai')
                        <div class="mt-4">
                            <h6 class="text-bold">
                                <i class="fa-regular fa-circle-right me-1"></i>
                                Detail permohonan
                            </h6>
                            <div class="ps-4">
                                <small class="text-muted">Tanggal Peninjauan</small>
                                <p>{{ Carbon\Carbon::parse($preview_izin->tanggal_peninjauan)->translatedFormat('d F Y H:i') . ' WITA' }}</p>
                                <small class="text-muted">Lokasi Izin</small>
                                <p>{{ $preview_izin->lokasi_izin }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h6 class="text-bold">
                                <i class="fa-regular fa-circle-right me-1"></i>
                                Persyaratan
                            </h6>
                            <div class="px-4">
                                @include('livewire.dashboard.pendaftaran.partials.preview-izin-persyaratan-show')
                            </div>
                        </div>
                    @elserole('pengelola_layanan')
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
                                    <p class="mt-3 mb-0 text-muted">Persyaratan</p>
                                    @include('livewire.dashboard.pendaftaran.partials.preview-izin-persyaratan-show')
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
                    @endrole
                </div>
            </div>
        @endif
        @role('pegawai')
            <div class="col-12">
                <div class="card p-4 position-relative mb-0">
                    <h5 class="text-bold mb-1">
                        <i class="fa-regular fa-circle-right me-1"></i>
                        Entri Data
                    </h5>
                    @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI)
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <p class="text-sm mb-0">Anda sudah melakukan entri data pada {{ $last_izin_status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA' }}</p>
                            @if (!$edit_entri_data)
                                <button class="btn btn-sm btn-secondary" type="button" wire:click='editEntriData'>
                                    edit
                                    <i class="fa-solid fa-pencil ms-1" wire:loading.remove wire:target='editEntriData'></i>
                                    <div class="spinner-border spinner-border-sm ms-1" role="status" wire:loading wire:target='editEntriData'>
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                            @endif
                        </div>
                    @endif
                    <form wire:submit.prevent='saveEntri'>
                        <div class="row">
                            @foreach ($entri_data as $entri)
                                @if ($entri->tipe == 'textarea')
                                    <div class="col-12 mb-3">
                                        @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SEDANG_ENTRI || $edit_entri_data)
                                            <label for="{{ $entri->id }}" class="mb-1">
                                                {{ $entri->nama }}
                                                @if ($entri->mandatori)
                                                    <span class="text-danger ms-1">*</span>
                                                @endif
                                            </label>
                                        @endif
                                        <div class="input-group input-group-outline">
                                            <textarea name="{{ $entri->id }}" class="form-control {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}" id="{{ $entri->id }}" rows="3" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}></textarea>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group input-group-outline">
                                            <label for="{{ $entri->id }}" class="form-label">
                                                {{ $entri->nama }}
                                                @if ($entri->mandatori)
                                                    <span class="text-danger ms-1">*</span>
                                                @endif
                                            </label>
                                            <input type="{{ $entri->tipe }}" class="form-control{{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}>
                                        </div>
                                        @if ($entri->is_multi_value)
                                            <p class="mb-0 mt-1 text-xs">
                                                <i class="fa-solid fa-info-circle me-1"></i>
                                                Untuk menambahkan beberapa data, pisahkan dengan simbol ";" (contoh: data1;data2;data3)
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SEDANG_ENTRI || $edit_entri_data)
                            <div class="d-flex align-items-center gap-3 mt-4">
                                @if ($edit_entri_data)
                                    <button class="btn btn-secondary" type="button" wire:click='cancelEditEntriData'>batal</button>
                                @else
                                    <button class="btn btn-secondary" type="reset">reset</button>
                                @endif
                                <button class="btn bg-gradient-primary" type="submit">simpan</button>
                            </div>
                        @endif
                    </form>
                    @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::BELUM_ENTRI)
                        <div class="position-absoslute inset-0 w-100 h-100 bg-white z-1 opacity-75 d-flex aling-items-center justify-content-center">
                            <button class="btn bg-gradient-primary d-flex align-items-center" type="button" wire:click='entriData'>
                                entri data
                                <div class="spinner-border spinner-border-sm ms-2" role="status" wire:loading wire:target='entriData'>
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endrole
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