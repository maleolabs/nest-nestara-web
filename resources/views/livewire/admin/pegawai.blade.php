@push('breadcrumb-item')
    <li class="breadcrumb-item active">
        Pegawai
    </li>
@endpush
<div class="row">
    <md-linear-progress wire:loading wire:target='selectTab' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="col-3 d-flex align-items-stretch">
        <div class="card w-100 min-vh-50">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::FRONT_OFFICE_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::FRONT_OFFICE_TAB }}')" onclick="switchTab('{{ self::FRONT_OFFICE_TAB }}')">
                        front office
                    </li>
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::BACK_OFFICE_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::BACK_OFFICE_TAB }}')" onclick="switchTab('{{ self::BACK_OFFICE_TAB }}')">
                        back office
                    </li>
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::PENANDATANGAN_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::PENANDATANGAN_TAB }}')" onclick="switchTab('{{ self::PENANDATANGAN_TAB }}')">
                        penandatangan
                    </li>
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::NEW_PEGAWAI_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::NEW_PEGAWAI_TAB }}')" onclick="switchTab('{{ self::NEW_PEGAWAI_TAB }}')">
                        buat pegawai baru
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-9 d-flex align-items-stretch">
        <div class="card w-100 position-relative">
            <div class="card-body">
                @if ($to_detail)
                    @include('livewire.admin.partials.pegawai-detail')
                @endif
                
                <div class="{{ !$to_detail && in_array($current_tab, [self::FRONT_OFFICE_TAB, self::BACK_OFFICE_TAB]) ? 'd-block' : 'd-none' }}">
                    <h5 class="fw-semibold">
                        Pegawai {{ str_capitalize(str_replace('-', ' ', $current_tab), true) }}
                    </h5>
                    <hr>
                    <div class="row">
                        @foreach ($users as $user)
                            <div class="col-4 mb-4">
                                <div class="dash-card-mono p-0 border">
                                    <img src="{{ asset('storage/users/' . $user->foto) }}" alt="{{ $user->name }}" class="card-mono-img">
                                    <div class="p-3">
                                        <h6 class="fw-medium btn-edit text-decoration-underline" wire:click='userDetail({{ $user->id }})'>
                                            {{ $user->pegawai->nama }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="{{ !$to_detail && $current_tab == self::PENANDATANGAN_TAB ? 'd-block' : 'd-none' }}">
                    <div class="row">
                        @foreach ($penandatangan as $atasan)
                            <div class="col-12 mb-4">
                                <div class="py-3 border-bottom d-flex align-items-top gap-3">
                                    <img src="{{ asset('storage/web/' . ($atasan->gender == 'male' ? 'male.png' : 'female.png')) }}" alt="{{ $atasan->nama }}" class="img-rounded bg-app-primary-light">
                                    <div>
                                        <h6 class="fw-medium btn-edit text-decoration-underline mb-0" id="modalRightShowBtn" wire:click='selectPenandatangan({{ $atasan->id }})'>
                                            {{ $atasan->nama }}
                                        </h6>
                                        <p class="mb-0 text-sm fw-medium">
                                            {{ $atasan->jabatan }}
                                            <span class="text-lg mx-1">&bull;</span>
                                            {{ $atasan->pangkat }}
                                        </p>
                                        <p class="mb-0 text-xs fw-medium">{{ $atasan->nip }}</p>
                                    </div>
                                    <button class="btn btn-transparent p-0 d-flex align-items-center justify-content-center rounded-circle text-danger shadow-none align-self-center ms-auto card-icon text-sm btn-edit" wire:click='deletePenandatangan({{ $atasan->id }})' wire:loading.class='disabled' wire:target='deletePenandatangan({{ $atasan->id }})'>
                                        <i class="fa-solid fa-trash" wire:loading.remove wire:target='deletePenandatangan({{ $atasan->id }})'></i>
                                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='deletePenandatangan({{ $atasan->id }})'></span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if (!$to_detail && $current_tab == self::NEW_PEGAWAI_TAB)
                    @livewire('admin.partials.pegawai-create')
                @endif
            </div>

            <div wire:loading wire:target='userDetail'>
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-75 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-right {{ !$selected_penandatangan ? 'd-none' : '' }}">
        <div class="modal-right-content modal-right-content-minimalist p-0" style="right: {{ !$selected_penandatangan ? '-40%' : '0' }}">
            <div class="px-4 py-2 border-bottom d-flex justify-content-between align-items-center gap-2">
                <h6 class="text-semibold mb-0">Penandatangan</h6>
                <button class="ms-auto text-md btn btn-transparent p-0 card-icon sticky-top-0 shadow-none modal-right-show-btn" id="modalRightCloseBtn">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="px-4 py-3 overflow-auto">
                <div wire:loading.remove wire:target='selectPenandatangan'>
                    @if ($selected_penandatangan)
                        <div class="row">
                            <div class="col-5 mb-3">
                                <p class="text-sm text-medium mb-0">Avatar</p>
                            </div>
                            <div class="col-7 mb-3">
                                <input type="radio" class="btn-check" name="gender" wire:model='penandatangan_form.gender' value="male" id="male" autocomplete="off">
                                <label class="btn btn-outline-primary rounded-3 text-capitalize" for="male">
                                    <img src="{{ asset('storage/web/male.png') }}" alt="male" class="img-rounded">
                                </label>

                                <input type="radio" class="btn-check" name="gender" wire:model='penandatangan_form.gender' value="female" id="female" autocomplete="off">
                                <label class="btn btn-outline-primary rounded-3 text-capitalize" for="female">
                                    <img src="{{ asset('storage/web/female.png') }}" alt="female" class="img-rounded">
                                </label>
                            </div>
                            <div class="col-5 mb-3">
                                <p class="text-sm text-medium mb-0">Nama</p>
                            </div>
                            <div class="col-7 mb-3">
                                <div class="input-group input-group-sm border rounded-3">
                                    <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.nama' placeholder="masukkan nama">
                                </div>
                            </div>
                            <div class="col-5 mb-3">
                                <p class="text-sm text-medium mb-0">Jabatan</p>
                            </div>
                            <div class="col-7 mb-3">
                                <div class="input-group input-group-sm border rounded-3">
                                    <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.jabatan' placeholder="masukkan jabatan">
                                </div>
                            </div>
                            <div class="col-5 mb-3">
                                <p class="text-sm text-medium mb-0">Pangkat</p>
                            </div>
                            <div class="col-7 mb-3">
                                <div class="input-group input-group-sm border rounded-3">
                                    <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.pangkat' placeholder="masukkan pangkat">
                                </div>
                            </div>
                            <div class="col-5 mb-3">
                                <p class="text-sm text-medium mb-0">NIP</p>
                            </div>
                            <div class="col-7 mb-3">
                                <div class="input-group input-group-sm border rounded-3">
                                    <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.nip' placeholder="masukkan nip">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div wire:loading.class='row' wire:target='selectPenandatangan'>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                    <div class="col-6" wire:loading wire:target='selectPenandatangan'>
                        <div class="loading-line"></div>
                    </div>
                </div>
            </div>
            <div class="modal-right-footer px-4 py-2 border-top d-flex justify-content-between z-2 bg-white">
                <div class="ms-auto d-flex align-items-center gap-2">
                    @if ($selected_penandatangan)
                        <button class="btn btn-sm bg-gradient-primary text-capitalize" type="button" wire:click='savePenandatangan'>
                            <span wire:loading.remove wire:target='savePenandatangan'>simpan</span>
                            <span class="spinner-border spinner-border-sm" wire:loading wire:target='savePenandatangan'></span>
                        </button>
                    @endif
                    <button class="btn btn-sm bg-gradient-light text-capitalize" type="button" id="modalRightCloseBtn" wire:click='cancelEditPenandatangan'>
                        batal
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    <script>
        function switchTab(tab) {
            let url = new URL(window.location)
            url.searchParams.set('tab', tab)
            window.history.pushState({}, '', url)
        }
    </script>
@endpush