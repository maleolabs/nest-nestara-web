<button class="btn btn-transparent shadow-none mb-3 text-capitalize rounded-pill" wire:click='showAll'>
    <span class="spinner-border spinner-border-sm me-1" wire:loading wire:target='showAll'></span>
    <i class="fa-solid fa-arrow-left me-1" wire:loading.remove wire:target='showAll'></i>
    kembali
</button>
<div class="row mt-3">
    <div class="col-3 text-center">
        <img src="{{ asset('storage/users/' . $user_detail->foto) }}" alt="{{ $user_detail->pegawai->nama }}" class="img-rounded img-rounded-md">
    </div>
    <div class="col-9">
        <p class="text-xs text-medium mb-0">Data Pegawai</p>
        <hr>
        <p class="text-sm text-semibold mb-1">Nama Pegawai</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div class="w-100">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='pegawai_form.nama' placeholder="masukkan nama pegawai">
                </div>
                @error('pegawai_form.nama')
                    <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
                @enderror
            </div>
            <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='pegawai_form.nama'></i>
        </div>
        <p class="text-sm text-semibold mb-1">Jabatan</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div class="w-100">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='pegawai_form.jabatan' placeholder="masukkan jabatan pegawai">
                </div>
                @error('pegawai_form.jabatan')
                    <div class="text-danger text-sm mt-1 fw-medium">{{ $message }}</div>
                @enderror
            </div>
            <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='pegawai_form.jabatan'></i>
        </div>
        <p class="text-sm text-semibold mb-1">Pangkat</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div class="w-100">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='pegawai_form.pangkat' placeholder="masukkan pangkat pegawai">
                </div>
                @error('pegawai_form.pangkat')
                    <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
                @enderror
            </div>
            <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='pegawai_form.pangkat'></i>
        </div>
        <p class="text-sm text-semibold mb-1">NIP</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div class="w-100">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='pegawai_form.nip' placeholder="masukkan nip pegawai">
                </div>
                @error('pegawai_form.nip')
                    <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
                @enderror
            </div>
            <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='pegawai_form.nip'></i>
        </div>
        <p class="text-xs">
            <i class="fa-solid fa-info-circle me-1"></i>
            untuk menyimpan perubahan tekan enter
        </p>
    </div>
</div>