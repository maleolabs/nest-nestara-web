@push('breadcrumb-item')
    <li class="breadcrumb-item active">
        Pegaturan
    </li>
@endpush
<div>
    <md-linear-progress wire:loading wire:target='selectTab' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="card mb-4">
        <div class="card-header border-bottom">
            <h6 class="fw-bold mb-0 text-capitalize">pengaturan surat</h6>
        </div>
        <div class="card-body">
            <p class="text-sm fw-semibold mb-1">Pengaturan Kop Surat</p>
            <div class="border rounded-3 p-4">
                <div class="row">
                    <div class="col-6 mb-3">
                        <p class="mb-1 text-xs fw-medium">nama pemerintah</p>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" class="form-control rounded-3 border-0" wire:model='kop_surat_form.nama_pemerintah' placeholder="masukkan nama pemerintah">
                        </div>
                        @error('kop_surat_form.nama_pemerintah')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <p class="mb-1 text-xs fw-medium">nama dinas</p>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" class="form-control rounded-3 border-0" wire:model='kop_surat_form.nama_dinas' placeholder="masukkan nama dinas">
                        </div>
                        @error('kop_surat_form.nama_dinas')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <p class="mb-1 text-xs fw-medium">alamat</p>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" class="form-control rounded-3 border-0" wire:model='kop_surat_form.alamat' placeholder="masukkan alamat dinas">
                        </div>
                        @error('kop_surat_form.alamat')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <p class="mb-1 text-xs fw-medium">website</p>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" class="form-control rounded-3 border-0" wire:model='kop_surat_form.website' placeholder="masukkan website">
                        </div>
                        @error('kop_surat_form.website')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm bg-gradient-primary text-capitalize" type="button" wire:click='saveKopSurat' wire:loading.class='disabled' wire:target='saveKopSurat'>
                        <span wire:loading.remove wire:target='saveKopSurat'>simpan</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveKopSurat'></span>
                    </button>
                </div>
            </div>

            <p class="text-sm fw-semibold mb-1 mt-4">Pengaturan Info Surat</p>
            <div class="border rounded-3 p-4">
                <div class="row">
                    <div class="col-6 mb-3">
                        <p class="mb-1 text-xs fw-medium">nomor surat terakhir</p>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="number" class="form-control rounded-3 border-0" wire:model='nomor_surat' placeholder="masukkan nomor surat terakhir">
                        </div>
                        @error('nomor_surat')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6 mb-3">
                        <p class="mb-1 text-xs fw-medium">tempat penetapan</p>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" class="form-control rounded-3 border-0" wire:model='tempat_penetapan' placeholder="masukkan tempat penetapan">
                        </div>
                        @error('tempat_penetapan')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm bg-gradient-primary text-capitalize" type="button" wire:click='saveInfoSurat' wire:loading.class='disabled' wire:target='saveInfoSurat'>
                        <span wire:loading.remove wire:target='saveInfoSurat'>simpan</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveInfoSurat'></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($ocr_setting)
        <div class="card">
            <div class="card-header border-bottom">
                <h6 class="fw-bold mb-0">Pengaturan OCR</h6>    
            </div>
            <div class="card-body p-4">
                <div class="border rounded-3 p-3">
                    <div class="row mb-3">
                        <div class="col-sm-5 mb-3">
                            <label for="api_token">
                                <p class="mb-1 text-sm fw-semibold">API Token</p>
                                <p class="text-xs mb-0">
                                    Masukkan API Key yang digunakan untuk mengautentikasi permintaan ke API OCR. Jangan bagikan API Key ini kepada pihak lain.
                                </p>
                            </label>
                        </div>
                        <div class="col-sm-7 mb-3">
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" class="form-control rounded-3 border-0" wire:model='api_key' placeholder="Masukkan API Key">
                            </div>
                        </div>
    
                        <div class="col-12 mb-3">
                            <hr>
                        </div>
    
                        <div class="col-sm-5 mb-3">
                            <label for="api_token">
                                <p class="mb-1 text-sm fw-semibold">Base URL API</p>
                                <p class="text-xs mb-0">
                                    URL dasar untuk mengakses layanan API OCR. Pastikan URL ini sesuai dengan lingkungan yang digunakan (misalnya produksi atau pengujian).
                                </p>
                            </label>
                        </div>
                        <div class="col-sm-7 mb-3">
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" class="form-control rounded-3 border-0" wire:model='base_url' placeholder="https://api.ocr-system.com/v1">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <hr>
                        </div>
                    </div>

                    @if($ocr_config_saved)
                        <div class="border border-success bg-success-light p-3 text-success text-sm rounded mb-3">
                            Berhasil menyimpan konfigurasi API OCR
                        </div>
                    @endif

                    <button class="btn btn-sm bg-gradient-primary text-capitalize" type="button" wire:click='saveOcrApiConfig' wire:loading.class='disabled' wire:target='saveOcrApiConfig'>
                        <span wire:loading.remove wire:target='saveOcrApiConfig'>simpan</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveOcrApiConfig'></span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>