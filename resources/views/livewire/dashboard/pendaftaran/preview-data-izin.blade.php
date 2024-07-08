<div>
    <md-linear-progress wire:loading wire:target='back, saveAction, saveEntri' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="row px-2">
        @if ($this->canAccess())
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
                        {{ $preview_izin->jenis_perizinan->kelompok_izin }}
                    </h6>
                    <h6 class="mb-0 spacing-normal">
                        <i class="fa-solid fa-hourglass-half mx-1"></i>
                        Durasi pengerjaan {{ $preview_izin->jenis_perizinan->durasi }}
                    </h6>
                    <h6 class="mb-0 spacing-normal">
                        <i class="fa-regular fa-calendar-check mx-1"></i>
                        Masa berlaku izin {{ $preview_izin->jenis_perizinan->masa_berlaku }}
                    </h6>
                    <div class="mt-4">
                        <h6 class="text-bold">
                            <i class="fa-regular fa-circle-right me-1"></i>
                            Status Izin
                        </h6>
                        @foreach ($preview_izin->status_izin as $status)
                            {!! renderStatusIzinWithIconAndDetails($status, true, true, true) !!}
                        @endforeach
                    </div>
                    @role('pegawai')
                        <div class="mt-4">
                            <h6 class="text-bold">
                                <i class="fa-regular fa-circle-right me-1"></i>
                                Detail permohonan
                            </h6>
                            <div class="ps-4">
                                <small class="text-muted">
                                    <i class="fa-regular fa-calendar-check me-1"></i>
                                    Tanggal Peninjauan
                                </small>
                                <h6 class="text-dark">{{ Carbon\Carbon::parse($preview_izin->tanggal_peninjauan)->translatedFormat('d F Y H:i') . ' WITA' }}</h6>
                                <small class="text-muted">
                                    <i class="fa-solid fa-building me-1"></i>
                                    Lokasi Izin
                                </small>
                                <h6 class="text-dark">{{ $preview_izin->lokasi_izin }}</h6>
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
                                                <input type="datetime" class="form-control disabled @error('tanggal_peninjauan')is-invalid @enderror" id="tanggal_peninjauan" disabled wire:model.live='tanggal_peninjauan'>
                                            </div>
                                            @error('tanggal_peninjauan')
                                                <div class="mb-3 text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                            <label for="lokasi_izin" class="form-label">Lokasi Izin</label>
                                            <div class="input-group input-group-outline mb-3">
                                                <input type="text" class="form-control @error('lokasi_izin')is-invalid @enderror" id="lokasi_izin" wire:model.live='lokasi_izin'>
                                            </div>
                                            @error('lokasi_izin')
                                                <div class="mb-3 text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <div class="input-group input-group-outline @error('keterangan')mb-1 @else mb-3 @enderror">
                                                <textarea rows="3" class="form-control @error('keterangan')is-invalid @enderror" id="keterangan" wire:model.live='keterangan'></textarea>
                                            </div>
                                            @error('keterangan')
                                                <div class="mb-3 text-danger text-sm">{{ $message }}</div>
                                            @enderror
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <div class="form-check ps-0">
                                                    <input type="radio" class="form-check-input" name="action_confirm" id="permohonan-selesai" wire:model.live='action_confirm' value="permohonan-selesai">
                                                    <label for="permohonan-selesai" class="form-check-label">Permohonan Selesai</label>
                                                </div>
                                                <div class="form-check ps-0">
                                                    <input type="radio" class="form-check-input" name="action_confirm" id="revisi-permohonan" wire:model.live='action_confirm' value="revisi-permohonan">
                                                    <label for="revisi-permohonan" class="form-check-label">Revisi Permohonan</label>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start gap-3">
                                                <button class="btn bg-gradient-primary{{ !$tanggal_peninjauan || !$lokasi_izin || !$action_confirm || ($action_confirm == 'revisi-permohonan' && !$keterangan) ? ' disabled' : '' }}" {{ !$tanggal_peninjauan || !$lokasi_izin || !$action_confirm || ($action_confirm == 'revisi-permohonan' && !$keterangan) ? 'disabled' : '' }} type="submit">Simpan</button>
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
            @role('pegawai')
                <div class="col-12">
                    <div class="card mb-0{{ $last_izin_status->keterangan != App\utils\enums\BackOfficeStatusEnum::BELUM_ENTRI && $last_izin_status->keterangan != App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI ? ' p-4' : '' }}">
                        @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::BELUM_ENTRI || $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI)
                            <div class="vh-30 position-relative">
                                <div class="position-absolute z-1 inset-0 w-100 h-100 p-4 vh-30 overflow-hidden">
                                    <h5 class="text-bold mb-1">
                                        <i class="fa-regular fa-circle-right me-1"></i>
                                        Entri Data
                                    </h5>
                                    <div class="row">
                                        @foreach ($entri_data as $entri)
                                            @if ($entri->tipe == 'textarea')
                                                <div class="col-12 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label class="form-label">
                                                            {{ $entri->nama }}
                                                            @if ($entri->mandatori)
                                                                <span class="text-danger ms-1">*</span>
                                                            @endif
                                                        </label>
                                                        <textarea class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-6 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label class="form-label">
                                                            {{ $entri->nama }}
                                                            @if ($entri->mandatori)
                                                                <span class="text-danger ms-1">*</span>
                                                            @endif
                                                        </label>
                                                        <input type="{{ $entri->tipe }}" class="form-control">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="position-absolute inset-0 w-100 h-100 z-2 d-flex flex-column gap-2 align-items-center justify-content-center bg-gradient-light-up-bottom rounded-3">
                                    @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI)
                                        <p class="mb-0 text-bold">{{ $last_izin_status->keterangan }}</p>
                                    @endif
                                    <button class="btn bg-gradient-primary d-flex align-items-center" type="button" wire:click='entriData'>
                                        entri data
                                        <div class="spinner-border spinner-border-sm ms-2" role="status" wire:loading wire:target='entriData'>
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        @else
                            <h5 class="text-bold mb-1">
                                <i class="fa-regular fa-circle-right me-1"></i>
                                Entri Data
                            </h5>
                            @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI)
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <p class="text-sm mb-0">Anda sudah melakukan entri data pada {{ $last_izin_status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA.' }}</p>
                                    @if (!$edit_entri_data)
                                        @if ($this->canEditEntriData())
                                            <button class="btn btn-sm btn-secondary" type="button" wire:click='editEntriData'>
                                                edit
                                                <i class="fa-solid fa-pencil ms-1" wire:loading.remove wire:target='editEntriData'></i>
                                                <div class="spinner-border spinner-border-sm ms-1" role="status" wire:loading wire:target='editEntriData'>
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </button>
                                        @else
                                            <span class="text-danger text-sm">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                Anda tidak mempunyai hak akses untuk mengubah data ini!
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            @endif
                            @if ($is_entri_changed)
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <p class="text-sm mb-0">{{ App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI }}</p>
                                    @if (!$edit_entri_data)
                                        @if ($this->thereIsOldEntriData())
                                            <button class="btn btn-sm btn-secondary" type="button" wire:click='getOldEntriData'>
                                                ambil data lama yang sesuai
                                                <i class="fa-solid fa-pencil ms-1" wire:loading.remove wire:target='getOldEntriData'></i>
                                                <div class="spinner-border spinner-border-sm ms-1" role="status" wire:loading wire:target='getOldEntriData'>
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endif
                            @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data)
                                <div class="row">
                                    @foreach ($entri_data as $entri)
                                        <div class="{{ $entri->tipe == 'textarea' ? 'col-12' : 'col-md-6' }} mb-3">
                                            <small class="text-muted">{{ $entri->nama }}</small>
                                            @if ($entri->is_multi_value && count(explode($entri->value_separator, $entri_data_izin[$entri->id])) > 1)
                                                @foreach (explode($entri->value_separator, $entri_data_izin[$entri->id]) as $item)
                                                    <h6 class="mb-0">{{ $loop->iteration . '. ' . $item }}</h6>
                                                @endforeach
                                            @else
                                                <h6 class="mb-0">{{ $entri->tipe == 'date' ? Carbon\Carbon::parse($entri_data_izin[$entri->id])->translatedFormat('d F Y') : $entri_data_izin[$entri->id] }}</h6>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <form wire:submit.prevent='saveEntri'>
                                    <div class="row">
                                        @foreach ($entri_data as $entri)
                                            @if ($entri->tipe == 'textarea')
                                                <div class="col-12 mb-3">
                                                    <div class="input-group input-group-outline">
                                                        <label for="{{ $entri->id }}" class="form-label">
                                                            {{ $entri->nama }}
                                                            @if ($entri->mandatori)
                                                                <span class="text-danger ms-1">*</span>
                                                            @endif
                                                        </label>
                                                        <textarea name="{{ $entri->id }}" class="form-control {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }} @error('entri_data_izin.' . $entri->id)is-invalid @enderror" id="{{ $entri->id }}" rows="3" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}></textarea>
                                                    </div>
                                                    @error('entri_data_izin.' . $entri->id)
                                                        <div class="text-danger text-sm">{{ str_replace('entri data izin.' . $entri->id, $entri->nama, $message) }}</div>
                                                    @enderror
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
                                                        <input type="{{ $entri->tipe }}" class="form-control{{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }} @error('entri_data_izin.' . $entri->id)is-invalid @enderror" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}>
                                                    </div>
                                                    @error('entri_data_izin.' . $entri->id)
                                                        <div class="text-danger text-sm">{{ str_replace('entri data izin.' . $entri->id, $entri->nama, $message) }}</div>
                                                    @enderror
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
                            @endif
                        @endif
                    </div>
                </div>
            @endrole
        @else
            <div class="position-absolute top-0 start-0 h-100 w-100 d-flex align-items-center justify-content-center">
                {{ $permission_message }}
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