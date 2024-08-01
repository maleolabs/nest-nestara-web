<div class="d-flex flex-column justify-content-between h-100">
    <h6 class="text-center fw-semibold">Buat Data Pegawai Baru</h6>

    <div class="my-5">
        @if ($step != self::RESULT_STEP)
            <h5 class="fw-semibold text-center text-capitalize mb-4">
                {{ $step }}
            </h5>
        @endif
        <div class="align-items-center justify-content-center w-50 flex-wrap mx-auto gap-3 {{ $step == self::ROLE_STEP ? 'd-flex' : 'd-none' }}">
            <input type="radio" class="btn-check" name="role" wire:model='role' value="{{ FRONT_OFFICE_ROLE }}" id="front-office" autocomplete="off">
            <label class="btn btn-outline-primary rounded-3 text-capitalize" for="front-office">Front Office</label>

            <input type="radio" class="btn-check" name="role" wire:model='role' value="{{ BACK_OFFICE_ROLE }}" id="back-office" autocomplete="off">
            <label class="btn btn-outline-primary rounded-3 text-capitalize" for="back-office">Back Office</label>

            <input type="radio" class="btn-check" name="role" wire:model='role' value="{{ PENANDATANGAN }}" id="penandatangan" autocomplete="off">
            <label class="btn btn-outline-primary rounded-3 text-capitalize" for="penandatangan">Penandatangan</label>
        </div>

        <div class="justify-content-center w-50 mx-auto flex-column {{ $step == self::PEGAWAI_STEP ? 'd-flex' : 'd-none' }}">
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    Nama Pegawai
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" name="new_pegawai_form.nama" placeholder="masukkan nama pegawai" wire:model='new_pegawai_form.nama'>
                </div>
                @error('new_pegawai_form.nama')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    Jabatan
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" name="new_pegawai_form.jabatan" placeholder="masukkan jabatan pegawai" wire:model='new_pegawai_form.jabatan'>
                </div>
                @error('new_pegawai_form.jabatan')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    Pangkat
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" name="new_pegawai_form.pangkat" placeholder="masukkan pangkat pegawai" wire:model='new_pegawai_form.pangkat'>
                </div>
                @error('new_pegawai_form.pangkat')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    NIP
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" name="new_pegawai_form.nip" placeholder="masukkan NIP pegawai" wire:model='new_pegawai_form.nip'>
                </div>
                @error('new_pegawai_form.nip')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="justify-content-center w-50 mx-auto flex-column {{ $step == self::USER_STEP ? 'd-flex' : 'd-none' }}">
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    Username
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" name="new_user_form.username" placeholder="masukkan username" wire:model='new_user_form.username'>
                </div>
                @error('new_user_form.username')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    Email
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="email" class="form-control border-0 rounded-3" name="new_user_form.email" placeholder="masukkan email" wire:model='new_user_form.email'>
                </div>
                @error('new_user_form.email')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="justify-content-center w-50 mx-auto flex-column {{ $step == self::PASSWORD_STEP ? 'd-flex' : 'd-none' }}">
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    Password
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="password" class="form-control border-0 rounded-3" name="new_password_form.password_baru" placeholder="masukkan password" wire:model='new_password_form.password_baru'>
                </div>
                @error('new_password_form.password_baru')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <p class="mb-1 text-xs fw-semibold">
                    Konfirmasi Password
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3">
                    <input type="password" class="form-control border-0 rounded-3" name="new_password_form.konfirmasi_password_baru" placeholder="konfirmasi password" wire:model='new_password_form.konfirmasi_password_baru'>
                </div>
                @error('new_password_form.konfirmasi_password_baru')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="justify-content-center align-items-center w-50 mx-auto flex-column gap-3 {{ $step == self::RESULT_STEP ? 'd-flex' : 'd-none' }}">
            <i class="fa-solid fs-1 {{ $success ? 'fa-circle-check text-success' : 'fa-xmark-circle text-danger' }}"></i>
            <p class="text-muted text-sm">{{ $result_message }}</p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        @if ($step == self::RESULT_STEP)
            <button class="btn bg-gradient-light rounded-pill text-capitalize mx-auto" wire:click='createNew' type="button">Buat data pegawai baru</button>
        @else
            @if ($step != self::ROLE_STEP)
                <button class="btn shadow-none btn-transparent rounded-pill text-capitalize" type="button" wire:click='prevStep'>
                    <i class="fa-solid fa-arrow-left text-sm me-1"></i>
                    sebelumnya
                </button>
            @endif

            <div class="ms-auto">
                @if ($step == self::PASSWORD_STEP || $role == PENANDATANGAN)
                    <button class="btn shadow-none bg-gradient-primary rounded-pill text-capitalize" type="button" wire:click='saveNewPegawai'>
                        simpan
                    </button>
                @else
                    <button class="btn shadow-none btn-transparent rounded-pill text-capitalize text-primary" type="button" wire:click='nextStep'>
                        berikutnya
                        <i class="fa-solid fa-arrow-right text-sm ms-1"></i>
                    </button>
                @endif
            </div>
        @endif
    </div>

    <div wire:loading wire:target='prevStep, nextStep, saveNewPegawai, createNew'>
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-75 d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
        </div>
    </div>
</div>