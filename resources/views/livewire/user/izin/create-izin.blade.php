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
                                <button class="btn btn-outline-secondary text-capitalize" type="button" wire:click='editExistingDataPemohon'>batal</button>
                                <button class="btn bg-gradient-primary text-capitalize" type="button" wire:click='saveChangeDataPemohon'>simpan perubahan</button>
                            </div>
                        @endif
                    @elseif ($form_step == self::SECOND_STEP)
                        <div class="border rounded p-3">
                            <h6 class="text-bold text-sm mb-3">Data Permohonan</h6>
                            <label for="id" class="form-label">Jenis Izin</label>
                            <div class="input-group input-group-outline">
                                <select class="form-select" id="jenisPerizinanSelect" wire:model='data_izin_form.jenis_izin'>
                                    @foreach ($allPerizinan as $perizinan)
                                        <option value="{{ $perizinan->jenis }}">{{ $perizinan->jenis }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('data_izin_form.jenis_izin')
                                <div class="text-sm text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
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
    </script>
@endpush