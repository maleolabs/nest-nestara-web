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
                <h6 class="fw-bold mb-0 text-capitalize">pengaturan ocr</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <p class="text-sm fw-semibold mb-1">API Token</p>
                    @if ($api_token)
                        <button class="btn btn-sm bg-gradient-info text-capitalize" type="button" wire:click='regenerateToken' wire:loading.attr='disabled' wire:loading.class='disabled' wire:target='regenerateToken'>
                            <span wire:loading.remove wire:target='regenerateToken'>regenerate token</span>
                            <span class="spinner-border spinner-border-sm" wire:loading wire:target='regenerateToken'></span>
                        </button>
                    @endif
                </div>
                <div class="border rounded-3 p-3">
                    @if ($api_token)
                        <div class="d-flex align-items-center mb-2">
                            <div>
                                <p class="fw-semibold text-sm text-capitalize text-primary mb-0">ocr api token</p>
                                @if ($token_used_count && $token_used_count > 0)
                                    <span class="text-muted fs-xs">Digunakan {{ $token_used_count }} kali.</span>
                                @endif
                            </div>
                            @if ($token_used_date)
                                <span class="ms-auto fs-xs">Terakhir digunakan {{ $token_used_date }}!</span>
                            @endif
                            <button class="btn btn-sm btn-outline-danger text-capitalize ms-2" wire:click='deleteToken' wire:loading.attr='disabled' wire:loading.class='disabled' wire:target='deleteToken'>
                                <span wire:loading.remove wire:target='deleteToken'>hapus token</span>
                                <span class="spinner-border spinner-border-sm" wire:loading wire:target='deleteToken'></span>
                            </button>
                        </div>
                        <p class="text-sm text-justify mb-0">
                            <span class="fw-bold">Kegunaan :</span> Token ini digunakan untuk memastikan komunikasi yang aman antara Aplikasi Utama Perizinan dan Aplikasi OCR. Token ini memungkinkan aplikasi utama untuk mengakses layanan OCR tanpa memerlukan autentikasi pengguna.
                        </p>

                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item border-0 text-sm">
                                Otorisasi Aman: Setiap permintaan dari aplikasi utama ke aplikasi OCR harus menyertakan token ini sebagai bukti autentikasi.
                            </li>
                            <li class="list-group-item border-0 text-sm">
                                Masa Berlaku: Token memiliki masa berlaku, dan setelah kedaluwarsa, token baru perlu dihasilkan.
                            </li>
                            <li class="list-group-item border-0 text-sm">
                                Penggunaan Khusus Sistem: Token ini digunakan hanya untuk komunikasi antar aplikasi, tanpa melibatkan data atau autentikasi pengguna.
                            </li>
                        </ol>

                        <p class="text-sm mt-2">
                            <span class="fw-bold">Token :</span>
                            <span class="rounded-3 bg-light py-1 px-2">{{ $api_token }}</span>
                        </p>
                        <p class="fst-italic fw-semibold fs-xs text-warning">Token akan expire pada {{ $token_expired_at }}</p>
                    @else
                        @if ($token_form_active)
                            <div class="row justify-content-center">
                                <div class="col-sm-6">
                                    <form wire:submit.prevent='createToken'>
                                        <h5 class="text-capitalize fw-bold">Buat Token Baru</h5>
    
                                        <p class="mb-1 text-xs fw-medium">nama token</p>
                                        <div class="input-group input-group-sm border rounded-3">
                                            <input type="text" class="form-control rounded-3 border-0" wire:model='nama_token' placeholder="masukkan nama token">
                                        </div>
                                        @error('nama_token')
                                            <div class="text-danger text-sm">{{ $message }}</div>
                                        @enderror
    
                                        <p class="mb-1 text-xs fw-medium mt-3">waktu kadaluarsa</p>
                                        <div class="input-group input-group-sm border rounded-3">
                                            <select name="token_expire" id="token_expire" wire:model='token_expire' class="form-select rounded-3 border-0">
                                                @foreach ($token_expire_op as $op => $val)
                                                    <option value="{{ $op }}">{{ $op }}</option>
                                                @endforeach
                                            </select>
                                        </div>
    
                                        <button type="submit" class="btn btn-sm bg-gradient-info mt-3">
                                            <span wire:loading.remove wire:target='createToken'>generate</span>
                                            <span class="spinner-border spinner-border-sm" wire:loading wire:target='createToken'></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-2 align-items-center justify-content-center vh-30">
                                <button class="btn btn-sm bg-gradient-info" type="button" wire:click='toggleTokenForm'>
                                    <span wire:loading.remove wire:target='toggleTokenForm'>generate token</span>
                                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='toggleTokenForm'></span>
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>