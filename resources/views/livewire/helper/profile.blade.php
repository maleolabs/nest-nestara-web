<div class="card">
    <div class="card-header border-bottom">
        <h6 class="card-title mb-0">Profil</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-sm-5 d-flex flex-column align-items-center">
                <img src="{{ $user_foto ? $user_foto->temporaryUrl() : asset('storage/users/' . $user->foto) }}" alt="{{ $user->username }}" class="img-rounded img-rounded-lg shadow-md">

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
            <div class="col-md-9 col-sm-7">
                @hasanyrole([FRONT_OFFICE_ROLE, BACK_OFFICE_ROLE])
                    <p class="text-sm mb-0 text-capitalize">data pegawai</p>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="nama_pegawai" class="mb-1 text-xs fw-medium text-capitalize">nama pegawai</label>
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" id="nama_pegawai" class="form-control border-0 rounded-3" wire:model='pegawai_form.nama'>
                            </div>
                            @error('pegawai_form.nama')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="jabatan" class="mb-1 text-xs fw-medium text-capitalize">jabatan</label>
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" id="jabatan" class="form-control border-0 rounded-3" wire:model='pegawai_form.jabatan'>
                            </div>
                            @error('pegawai_form.jabatan')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="pangkat" class="mb-1 text-xs fw-medium text-capitalize">pangkat</label>
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" id="pangkat" class="form-control border-0 rounded-3" wire:model='pegawai_form.pangkat'>
                            </div>
                            @error('pegawai_form.pangkat')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="nip" class="mb-1 text-xs fw-medium text-capitalize">nip</label>
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" id="nip" class="form-control border-0 rounded-3" wire:model='pegawai_form.nip'>
                            </div>
                            @error('pegawai_form.nip')
                                <div class="text-danger text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3 mb-5">
                        <button class="btn btn-sm bg-gradient-light text-capitalize" wire:click='saveDataPegawai' wire:loading.class='disabled' wire:target='saveDataPegawai'>
                            <span wire:loading.remove wire:target='saveDataPegawai'>simpan</span>
                            <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveDataPegawai'></span>
                        </button>
                    </div>
                @endhasanyrole
                <p class="text-sm mb-0 text-capitalize">data pengguna</p>
                <hr>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="nama" class="mb-1 text-xs fw-medium text-capitalize">nama</label>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" id="nama" class="form-control border-0 rounded-3" wire:model='user_form.name'>
                        </div>
                        @error('user_form.name')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="username" class="mb-1 text-xs fw-medium text-capitalize">username</label>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" id="username" class="form-control border-0 rounded-3" wire:model='user_form.username'>
                        </div>
                        @error('user_form.username')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="email" class="mb-1 text-xs fw-medium text-capitalize">email</label>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="email" id="email" class="form-control border-0 rounded-3" wire:model='user_form.email'>
                        </div>
                        @error('user_form.email')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm bg-gradient-light text-capitalize" wire:click='saveDataPengguna' wire:loading.class='disabled' wire:target='saveDataPengguna'>
                        <span wire:loading.remove wire:target='saveDataPengguna'>simpan</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveDataPengguna'></span>
                    </button>
                </div>

                <p class="text-sm mb-0 mt-5 text-capitalize">ubah password</p>
                <hr>
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="password_sekarang" class="mb-1 text-xs fw-medium text-capitalize">password sekarang</label>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="password" id="password_sekarang" class="form-control border-0 rounded-3" wire:model='password_form.password_lama'>
                        </div>
                        @error('password_form.password_lama')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="password_baru" class="mb-1 text-xs fw-medium text-capitalize">password baru</label>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="password" id="password_baru" class="form-control border-0 rounded-3" wire:model='password_form.password_baru'>
                        </div>
                        @error('password_form.password_baru')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="konfirmasi_password_baru" class="mb-1 text-xs fw-medium text-capitalize">konfirmasi password baru</label>
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="password" id="konfirmasi_password_baru" class="form-control border-0 rounded-3" wire:model='password_form.konfirmasi_password_baru'>
                        </div>
                        @error('password_form.konfirmasi_password_baru')
                            <div class="text-danger text-sm">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-sm bg-gradient-light text-capitalize" wire:click='savePassword' wire:loading.class='disabled' wire:target='savePassword'>
                        <span wire:loading.remove wire:target='savePassword'>simpan</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='savePassword'></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>