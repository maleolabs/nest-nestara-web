<button class="btn btn-transparent shadow-none mb-3 text-capitalize rounded-pill" wire:click='showAll'>
    <span class="spinner-border spinner-border-sm me-1" wire:loading wire:target='showAll'></span>
    <i class="fa-solid fa-arrow-left me-1" wire:loading.remove wire:target='showAll'></i>
    kembali
</button>
<div class="row mt-3">
    <div class="col-3 text-center">
        <img src="{{ $user_foto ? $user_foto->temporaryUrl() : asset('storage/users/' . $user_detail->foto) }}" alt="{{ $user_detail->pegawai->nama }}" class="img-rounded img-rounded-md shadow-sm">

        <div class="d-flex align-items-center gap-2 justify-content-center mt-3">
            <button class="btn btn-sm text-capitalize {{ $user_foto ? 'bg-gradient-primary' : 'bg-gradient-light' }}" type="button" @if($user_foto) wire:click='saveNewFoto' @else onclick="document.getElementById('userFoto').click()" @endif wire:loading.class='disabled' wire:target='saveNewFoto, user_foto'>
                <span class="spinner-border spinner-border-sm" wire:loading wire:target='user_foto, saveNewFoto'></span>
                <i class="fa-solid {{ $user_foto ? 'fa-upload' : 'fa-pencil' }} text-sm" wire:loading.remove wire:target='user_foto'></i>
                <span class="ms-1">{{ $user_foto ? 'simpan' : 'ubah foto' }}</span>
            </button>
            <input type="file" hidden id="userFoto" wire:model='user_foto'>
            @if (!$foto_is_default)
                <button class="btn btn-sm bg-gradient-danger" type="button" wire:click='deleteFoto' wire:loading.class='disabled'>
                    <i class="fa-solid fa-trash text-sm" wire:loading.remove wire:target='deleteFoto'></i>
                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='deleteFoto'></span>
                </button>
            @endif
        </div>
    </div>
    <div class="col-9">
        <p class="text-xs text-medium mb-0">Data Pegawai</p>
        <hr>
        <p class="text-sm fw-medium mb-1">Nama Pegawai</p>
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
        <p class="text-sm fw-medium mb-1">Jabatan</p>
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
        <p class="text-sm fw-medium mb-1">Pangkat</p>
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
        <p class="text-sm fw-medium mb-1">NIP</p>
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

        <p class="text-xs text-medium mb-0 mt-5">Data Pengguna</p>
        <hr>
        <p class="text-sm fw-medium mb-1">Nama</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div class="w-100">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='user_form.name' placeholder="masukkan nama">
                </div>
                @error('user_form.name')
                    <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
                @enderror
            </div>
            <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='user_form.name'></i>
        </div>
        <p class="text-sm fw-medium mb-1">Username</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div class="w-100">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='user_form.username' placeholder="masukkan username">
                </div>
                @error('user_form.username')
                    <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
                @enderror
            </div>
            <i class="fa-solid fa-spinner fa-spin pulse" wire:loading wire:target='user_form.username'></i>
        </div>
        <p class="text-sm fw-medium mb-1">Email</p>
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div class="w-100">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='user_form.email' placeholder="masukkan email">
                </div>
                @error('user_form.email')
                    <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
                @enderror
            </div>
            <i class="fa-solid fa-spinner fa-spin pulse" wire:loading wire:target='user_form.email'></i>
        </div>
        <p class="text-xs">
            <i class="fa-solid fa-info-circle me-1"></i>
            untuk menyimpan perubahan tekan enter
        </p>

        <p class="text-xs text-medium mb-0 mt-5">Password</p>
        <hr>
        @if ($password_saved)
            <div class="message-success-tip text-success bg-app-primary-light border-success rounded-3 p-3 mb-3 text-sm">
                Password berhasil diperbaharui!
            </div>
        @endif
        <form wire:submit.prevent='savePassword'>
            <p class="text-sm fw-medium mb-1">Password Baru</p>
            <div class="input-group input-group-sm border rounded-3">
                <input type="password" class="form-control border-0 rounded-3" wire:model.live='password_form.password_baru' placeholder="masukkan password baru">
            </div>
            @error('password_form.password_baru')
                <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
            @enderror
            <p class="text-sm fw-medium mb-1 mt-3">Konfirmasi Password Baru</p>
            <div class="input-group input-group-sm border rounded-3">
                <input type="password" class="form-control border-0 rounded-3" wire:model.live='password_form.konfirmasi_password_baru' placeholder="konfirmasi password baru">
            </div>
            @error('password_form.konfirmasi_password_baru')
                <div class="text-danger text-sm fw-medium mt-1">{{ $message }}</div>
            @enderror
    
            @if ($password_form->password_baru && $password_form->konfirmasi_password_baru)
                <button class="btn btn-sm bg-gradient-primary text-capitalize mt-3" type="submit" wire:loading.class='disabled' wire:target='savePassword'>
                    <span wire:loading.remove wire:target='savePassword'>simpan</span>
                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='savePassword'></span>
                </button>
            @endif
        </form>
    </div>
</div>