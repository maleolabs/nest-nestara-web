<div>
    @if ($preview_page)
        @livewire($preview_page, ['preview_izin_id' => $preview_izin_id])
    @else
        <md-linear-progress wire:loading wire:target='back, saveAction, saveEntri, saveTinjauanLapangan' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

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
                        <div class="d-flex flex-sm-row flex-column justify-content-sm-between align-items-sm-top align-items-between gap-3">
                            <div>
                                <p class="mb-0 text-sm">Nama Izin</p>
                                <h3 class="">{{ str_capitalize($preview_izin->jenis_perizinan->nama, true) }}</h3>
                                <h6 class="mb-0 ">
                                    <i class="fa-solid fa-layer-group"></i>
                                    {{ $preview_izin->jenis_perizinan->kelompok_izin }}
                                </h6>
                                <h6 class="mb-0 ">
                                    <i class="fa-solid fa-hourglass-half mx-1"></i>
                                    Durasi pengerjaan {{ $preview_izin->jenis_perizinan->durasi }}
                                </h6>
                                <h6 class="mb-0 ">
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
                            </div>
                        </div>
                        @if($this->canAccess() && $preview_izin->lokasi_izin !== null && $last_izin_status->nama !== App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN)
                            <div class="mt-4">
                                <h6 class="text-bold">
                                    <i class="fa-regular fa-circle-right me-1"></i>
                                    Detail permohonan
                                </h6>
                                <div class="ps-4">
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
                                    @include('livewire.dashboard.preview-izin.preview-izin-persyaratan-show')
                                </div>
                            </div>
                        @endif
                        @role(FRONT_OFFICE_ROLE)
                            @if ($preview_izin->lokasi_izin === null || $last_izin_status->nama == App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN)
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
                                            @include('livewire.dashboard.preview-izin.preview-izin-persyaratan-show')
                                        </div>
                                        <div class="tab-pane fade @if($active_tab == self::TINDAKAN)show active @endif" id="tindakan-tab">
                                            @if ($this->canProcess())
                                                <form wire:submit.prevent='saveAction'>
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
                                                        <button class="btn bg-gradient-primary{{ !$lokasi_izin || !$action_confirm || ($action_confirm == 'revisi-permohonan' && !$keterangan) ? ' disabled' : '' }}" {{ !$lokasi_izin || !$action_confirm || ($action_confirm == 'revisi-permohonan' && !$keterangan) ? 'disabled' : '' }} type="submit">Simpan</button>
                                                    </div>
                                                </form>
                                            @else
                                                <div class="alert alert-light">
                                                    <p class="mt-3 text-muted">
                                                        <i class="fa-solid fa-circle-info me-1"></i>
                                                        Proses perbaikan persyaratan oleh pemohon
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endrole
                    </div>
                </div>

                @if ($this->canAccessSavedEntriData())
                    <div class="col-12 mb-4">
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="text-bold mb-4">
                                        <i class="fa-regular fa-circle-right me-1"></i>
                                        Entri Data
                                    </h6>
                                    @if ($this->alreadyEntriData())
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-sm btn-warning" wire:click='reEditEntriData' wire:loading.remove wire:target='reEditEntriData'>
                                                <span wire:loading.remove wire:target='reEditEntriData'>Edit Entri Data</span>
                                                <span class="spinner-border spinner-border-sm" role="status" wire:loading wire:target='reEditEntriData'>
                                                </span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && $last_izin_status->keterangan != App\utils\enums\BackOfficeStatusEnum::ENTRI_TINJAUAN_LAPANGAN)
                                    @include('livewire.dashboard.preview-izin.button-edit-entri-data')
                                @endif
                                @if ($is_entri_changed && !$preview_izin->ambil_entri_lama)
                                    @include('livewire.dashboard.preview-izin.button-entri-has-changed')
                                @endif
                                @include('livewire.dashboard.preview-izin.saved-entri-data')
                            </div>
                        </div>
                    </div>
                @endif

                @role(BACK_OFFICE_ROLE)
                    <div class="col-12">
                        @if ($this->hasEntriDataForm())
                            @if ($this->isFormNotReady())
                                <div class="card mb-4">
                                    @include('livewire.dashboard.preview-izin.button-entri-data')
                                </div>
                            @else
                                @if ($this->isFormNotEntered())
                                    @if ($this->isFormFillAuthorized())
                                        <div class="card mb-4">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="text-bold mb-1">
                                                        <i class="fa-regular fa-circle-right me-1"></i>
                                                        Entri Data
                                                    </h6>
                                                    @if(get_pengaturan('CLIENT_TOKEN') != null)
                                                        <button class="btn btn-sm bg-gradient-info text-capitalize" type="button" wire:click='automaticFill'>
                                                            <span wire:loading.remove wire:target='automaticFill'>isi otomatis</span>
                                                            <span class="spinner-border spinner-border-sm" role="status" wire:loading wire:target='automaticFill'></span>
                                                        </button>
                                                    @endif
                                                </div>
                                                @include('livewire.dashboard.preview-izin.form-entri-data')
                                            </div>
                                        </div>
                                    @else
                                        <div class="card mb-0">
                                            @include('livewire.dashboard.preview-izin.filling-entri-form-not-authorized-alert')
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @else
                            <div class="alert alert-light">
                                <p class="mt-3 text-muted">
                                    <i class="fa-solid fa-circle-info me-1"></i>
                                    Tidak ada formulir data entri untuk perizinan ini!
                                </p>
                            </div>
                        @endif

                        @if ($this->tinjauLapangan())
                            <div class="card mb-0" id="timTeknis">
                                <div class="card-body">
                                    @include('livewire.dashboard.back-office.partials.penjadwalan-tim-form')
                                </div>
                            </div>
                        @endif

                    </div>
                @endrole
            @else
                <div class="position-absolute top-0 start-0 h-100 w-100 d-flex align-items-center justify-content-center">
                    {{ $permission_message }}
                </div>
            @endif
        </div>
        @include('livewire.dashboard.preview-izin.modal-show-document')
        @livewire('dashboard.dashboard-util')
    @endif
</div>