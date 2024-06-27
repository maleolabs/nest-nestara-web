<div class="container mb-5">
    <md-linear-progress wire:loading wire:target='save, nextStep, prevStep, editExistingDataPemohon, saveChangeDataPemohon' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <h5 class="text-bold mb-0 text-center">
        <i class="fa-solid fa-file-circle-plus me-1"></i>
        Formulir Pengisian Data Izin Baru
    </h5>
    <p class="text-muted text-sm text-center">mohon untuk mengisi semua data berikut!</p>
    <div class="form-progress-container mb-3">
        <div class="form-step {{ $form_step == self::FIRST_STEP ? 'active' : ($form_step == self::SECOND_STEP || $form_step == self::LAST_STEP ? 'fill' : '') }}">
            <p class="mb-0">Data Pemohon</p>
            <div class="step-bullet">
                <span></span>
            </div>
            <i class="step-check fa-solid fa-check"></i>
        </div>
        <div class="form-step {{ $form_step == self::SECOND_STEP ? 'active' : ($form_step == self::LAST_STEP ? 'fill' : '') }}">
            <p class="mb-0">Izin & Persyaratan</p>
            <div class="step-bullet">
                <span></span>
            </div>
            <i class="step-check fa-solid fa-check"></i>
        </div>
        <div class="form-step {{ $form_step == self::LAST_STEP ? 'active' : '' }}">
            <p class="mb-0">Konfirmasi</p>
            <div class="step-bullet">
                <span></span>
            </div>
            <i class="step-check fa-solid fa-check"></i>
        </div>
    </div>
    <form wire:submit.prevent='save'>
        @csrf
        <div class="row">
            <div class="col-sm-8 mb-sm-0 mb-4">
                @if ($user_already_has_data_pemohon && $form_step == self::FIRST_STEP && !$edit_existing_data_pemohon)
                    <div class="card bg-quaternary p-4 position-relative overflow-hidden">
                        <div class="position-absolute top-100 start-100 translate-middle rounded-circle bg-tertiary shadow-lg box-200" style="z-index: 0;"></div>
                        <div style="z-index: 1">
                            <h4 class="text-bold text-capitalize">{{ $form_data_pemohon->nama_pemohon }}</h4>
                            <p class="mb-0 text-sm text-bold">
                                <i class="fa-regular fa-id-card me-1"></i>
                                {{ $form_data_pemohon->nomor_id }}
                                <span class="text-uppercase">{{ "({$form_data_pemohon->sumber_identitas})" }}</span>
                            </p> 
                            <p class="mb-0 text-sm text-bold">
                                <i class="fa-solid fa-phone me-1"></i>
                                {{ $form_data_pemohon->no_telepon }}
                            </p> 
                            <p class="mb-3 text-xs text-bold text-uppercase">
                                <i class="fa-solid fa-location-dot me-2"></i>
                                {{ $this->getAlamat() }}
                            </p>
                            <button wire:click='editExistingDataPemohon' class="btn btn-outline-primary border-2 text-capitalize" type="button">
                                ubah
                            </button>
                        </div>
                    </div>
                @endif
                <div class="card p-4 mb-0 {{ $user_already_has_data_pemohon && $form_step == self::FIRST_STEP && !$edit_existing_data_pemohon ? 'd-none' : '' }}">
                    @if ($form_step == self::FIRST_STEP)
                        @if (!$user_already_has_data_pemohon || $edit_existing_data_pemohon)
                            @include('livewire.dashboard.pendaftaran.partials.data-pemohon-form-field')
                            <div class="d-flex align-items-center justify-content-sm-start justify-content-around gap-sm-2">
                                @if ($edit_existing_data_pemohon)
                                    <button class="btn btn-outline-secondary text-capitalize" type="button" wire:click='editExistingDataPemohon'>batal</button>
                                @endif
                                <button class="btn bg-gradient-primary text-capitalize" type="button" wire:click='saveChangeDataPemohon'>simpan perubahan</button>
                            </div>
                        @endif
                    @elseif ($form_step == self::SECOND_STEP)
                        <div class="border rounded p-3">
                            <h6 class="text-bold text-sm mb-3">Data Permohonan</h6>
                            <label for="id" class="form-label">Jenis Izin</label>
                            <div class="input-group input-group-outline mb-3">
                                <select class="form-select" id="jenisPerizinanSelect">
                                    <option selected>Pilih jenis perizinan</option>
                                    @foreach ($allPerizinan as $perizinan)
                                        <option value="{{ $perizinan->nama }}">{{ $perizinan->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <ul class="border rounded p-3 position-relative">
                                <h6 class="text-bold mb-0">Persyaratan Izin</h6>
                                <p class="text-sm">Lengkapi persyaratan berikut ini.</p>
                                @if ($selectedPerizinan)
                                    @forelse ($selectedPerizinan->syarat_izin as $syarat)
                                        <label for="syarat{{ $syarat->id }}" class="form-label text-lowercase">{{ $syarat->nama . " ({$syarat->tipe})" }}</label>
                                        <div class="input-group input-group-outline mb-3 flex-column"
                                            x-data="{ uploading: false, progress: 0 }"
                                            x-on:livewire-upload-start="uploading = true"
                                            x-on:livewire-upload-finish="uploading = false"
                                            x-on:livewire-upload-cancel="uploading = false"
                                            x-on:livewire-upload-error="uploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            @php
                                                $type = $syarat->tipe == 'pdf' || $syarat->tipe == 'img' ? 'file' : 'text';
                                            @endphp
                                            <input type="{{ $type }}" class="form-control w-100" wire:model='persyaratan.{{ $syarat->id }}'>
                                            <div x-show="uploading" class="progress mt-2 w-50" role="progressbar" aria-label="Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped bg-success" x-bind:style="`width: ${progress}%`" style="--bs-progress-bg: var(--color-primary);"></div>
                                            </div>
                                        </div>
                                        @if ($syarat->tipe == 'img' && isset($persyaratan[$syarat->id]))
                                            <div class="mb-3 w-50 rounded shadow">
                                                <img src="{{ $persyaratan[$syarat->id]->temporaryUrl() }}" alt="" class="img-fluid">
                                            </div>
                                        @endif
                                    @empty
                                        <p class="text-sm text-center">Tidak ada persyaratan</p>
                                    @endforelse
                                @else
                                    <p class="text-sm text-center">Silahkan pilih jenis izin</p>    
                                @endif
                                <div wire:loading wire:target='data_izin_form.jenis_izin'>
                                    <div class="position-absolute top-0 start-0 bg-white opacity-75 d-flex align-items-center justify-content-center w-100 h-100">
                                        <div class="spinner-border me-2" role="status"></div>
                                        <span>loading...</span>
                                    </div>
                                </div>
                            </ul>
                            @error('data_izin_form.jenis_izin')
                                <div class="text-sm text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @elseif($form_step == self::LAST_STEP)
                        <h5 class="mb-0">Data Pemohon</h5>
                        <hr>
                        <p class="mb-0 text-muted text-sm">Sumber Identitas</p>
                        <h6 class="mb-3 text-uppercase">{{ $form_data_pemohon->sumber_identitas }}</h6>
                        <p class="mb-0 text-muted text-sm">Nomor Identitas</p>
                        <h6 class="mb-3 text-capitalize">{{ $form_data_pemohon->nomor_id }}</h6>
                        <p class="mb-0 text-muted text-sm">Nama</p>
                        <h6 class="mb-3 text-capitalize">{{ $form_data_pemohon->nama_pemohon }}</h6>
                        <p class="mb-0 text-muted text-sm">Nomor Telepon</p>
                        <h6 class="mb-3 text-capitalize">{{ $form_data_pemohon->no_telepon }}</h6>
                        <p class="mb-0 text-muted text-sm">Alamat</p>
                        <h6 class="mb-3 text-capitalize">{{ $this->getAlamat() }}</h6>
                        @if ($form_data_pemohon->alamat_luar_negeri)
                            <p class="mb-0 text-muted text-sm">Alamat Luar Negeri</p>
                            <h6 class="mb-3 text-capitalize">{{ $form_data_pemohon->alamat_luar_negeri }}</h6>
                        @endif
                        <h5 class="mt-3 mb-0">Data Izin</h5>
                        <hr>
                        <p class="mb-0 text-muted text-sm">Jenis Izin</p>
                        <h6 class="mb-3 text-capitalize">{{ $data_izin_form->jenis_izin }}</h6>
                        <p class="mb-0 text-muted text-sm">Persyaratan</p>
                        @foreach ($persyaratan as $syarat_id => $syarat_izin)
                            @php($syarat_model = $this->getSyaratIzinService()->find($syarat_id))
                            <h6 class="text-capitalize">{{ $syarat_model->nama }}</h6>
                            @if ($syarat_model->tipe == 'img')
                                <div class="w-50 mb-3">
                                    <img src="{{ $syarat_izin->temporaryUrl() }}" alt="" class="img-fluid shadow">
                                </div>
                            @else
                                <p class="text-muted mb-3">
                                    {{ $syarat_izin->getClientOriginalName() }}
                                    <a href="{{ $syarat_izin->temporaryUrl() }}" target="blank" class="ms-2">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                </p>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card p-4">
                    @if ($form_step == self::FIRST_STEP)
                        <button class="btn btn-app-outline-primary w-100 mt-3" id="btnNextStep" type="button" wire:click='nextStep'>berikutnya</button>
                    @elseif ($form_step == self::SECOND_STEP)
                        <button class="btn btn-app-outline-primary w-100 mt-3" id="btnNextStep" type="button" wire:click='nextStep'>berikutnya</button>
                        <button class="btn btn-outline-secondary w-100 mt-3" type="button" wire:click='prevStep'>sebelumnya</button>
                    @elseif ($form_step == self::LAST_STEP)
                        <h6 class="text-md">Info</h6>
                        <p class="text-xs text-secondary">
                            Pastikan semua data yang Anda masukkan sudah benar sebelum menekan tombol "Simpan". Setelah menekan tombol "Simpan", data akan diproses dan disimpan dalam sistem. Anda masih dapat mengedit informasi yang sudah disimpan dengan mengakses halaman edit jika diperlukan. 
                        </p>
                        <hr class="mb-2 mt-0">
                        <button class="btn bg-gradient-primary w-100 mt-3" type="submit">simpan</button>
                        <button class="btn btn-outline-secondary w-100 mt-3" type="button" wire:click='prevStep'>sebelumnya</button>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>
@push('scripts')
    <script>
         Livewire.hook('morph.updated', ({ el, component }) => {
            select2Init()
            observeSelect2Value()
        })
        function select2Init() {
            $('#jenisPerizinanSelect').select2({
                placeholder: 'Pilih jenis perizinan',
                theme: "bootstrap-5",
            });
        }

        let btnNextStep = document.querySelectorAll("#btnNextStep")
        btnNextStep.forEach(btnNext => {
            btnNext.addEventListener('click', function() {
                window.scrollTo(0,0)
            })  
        })

        function observeSelect2Value() {
            const select2El = document.querySelector('.select2-selection__rendered')
            if (select2El) {
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'characterData' || mutation.type === 'childList') {
                            @this.set('data_izin_form.jenis_izin', select2El.textContent)
                        }
                    });
                });
                const config = {
                    childList: true,
                    subtree: true,
                    characterData: true
                }
                observer.observe(select2El, config)
            }
        }
    </script>
@endpush