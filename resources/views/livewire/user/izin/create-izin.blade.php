<div class="container">
    <md-linear-progress wire:loading wire:target='save' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <h5 class="text-bold mb-0">
        <i class="fa-solid fa-file-circle-plus me-1"></i>
        Formulir Pengisian Data Izin Baru
    </h5>
    <p class="text-muted text-sm">mohon untuk mengisi semua data berikut!</p>
    <form wire:submit.prevent='save'>
        @csrf
        <div class="row">
            <div class="col-sm-8 mb-sm-0 mb-4">
                <div class="card p-4 mb-0">
                    @include('livewire.dashboard.pendaftaran.partials.data-pemohon-form-field')
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card p-4">
                    <h6 class="text-md">Info</h6>
                    <p class="text-xs text-secondary">
                        Pastikan semua data yang Anda masukkan sudah benar sebelum menekan tombol "Simpan". Setelah menekan tombol "Simpan", data akan diproses dan disimpan dalam sistem. Anda masih dapat mengedit informasi yang sudah disimpan dengan mengakses halaman edit jika diperlukan. 
                    </p>
                    <hr class="mb-2 mt-0">
                    <button class="btn bg-gradient-primary w-100 mt-3" type="submit">simpan</button>
                    <button class="btn btn-outline-secondary w-100 mt-3" type="reset" wire:click='resetData'>reset</button>
                </div>
            </div>
        </div>
    </form>
</div>