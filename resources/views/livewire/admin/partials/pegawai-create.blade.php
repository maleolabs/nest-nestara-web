<div class="d-flex flex-column justify-content-between h-100">
    <h6 class="text-center fw-semibold">Buat Data Pegawai Baru</h6>

    <div class="my-5">
        <h5 class="fw-semibold text-center text-capitalize mb-4">
            {{ $step }}
        </h5>
        @if ($step == self::ROLE_STEP)
            <div class="d-flex align-items-center justify-content-center w-50 flex-wrap mx-auto gap-3">
                <input type="radio" class="btn-check" name="role" wire:model='role' value="{{ FRONT_OFFICE_ROLE }}" id="front-office" autocomplete="off">
                <label class="btn btn-outline-primary rounded-3 text-capitalize" for="front-office">Front Office</label>

                <input type="radio" class="btn-check" name="role" wire:model='role' value="{{ BACK_OFFICE_ROLE }}" id="back-office" autocomplete="off">
                <label class="btn btn-outline-primary rounded-3 text-capitalize" for="back-office">Back Office</label>

                <input type="radio" class="btn-check" name="role" wire:model='role' value="{{ PENANDATANGAN }}" id="penandatangan" autocomplete="off">
                <label class="btn btn-outline-primary rounded-3 text-capitalize" for="penandatangan">Penandatangan</label>
            </div>
        @elseif ($step == self::PEGAWAI_STEP)
            <div class="d-flex justify-content-center w-50 mx-auto flex-column">
                <p class="mb-1 text-xs fw-semibold">
                    Nama Pegawai
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="text" class="form-control border-0 rounded-3" placeholder="masukkan nama pegawai" wire:model='pegawai_form.nama'>
                </div>
                <p class="mb-1 text-xs fw-semibold">
                    Jabatan
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="text" class="form-control border-0 rounded-3" placeholder="masukkan jabatan pegawai" wire:model='pegawai_form.jabatan'>
                </div>
                <p class="mb-1 text-xs fw-semibold">
                    Pangkat
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="text" class="form-control border-0 rounded-3" placeholder="masukkan pangkat pegawai" wire:model='pegawai_form.pangkat'>
                </div>
                <p class="mb-1 text-xs fw-semibold">
                    NIP
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="text" class="form-control border-0 rounded-3" placeholder="masukkan NIP pegawai" wire:model='pegawai_form.nip'>
                </div>
            </div>
        @elseif ($step == self::USER_STEP)
            <div class="d-flex justify-content-center w-50 mx-auto flex-column">
                <p class="mb-1 text-xs fw-semibold">
                    Username
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="text" class="form-control border-0 rounded-3" placeholder="masukkan username" wire:model='user_form.username'>
                </div>
                <p class="mb-1 text-xs fw-semibold">
                    Email
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="email" class="form-control border-0 rounded-3" placeholder="masukkan email" wire:model='user_form.email'>
                </div>
            </div>
        @elseif ($step == self::PASSWORD_STEP)
            <div class="d-flex justify-content-center w-50 mx-auto flex-column">
                <p class="mb-1 text-xs fw-semibold">
                    Password
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="password" class="form-control border-0 rounded-3" placeholder="masukkan password" wire:model='password_form.password_baru'>
                </div>
                <p class="mb-1 text-xs fw-semibold">
                    Konfirmasi Password
                    <span class="text-danger ms-1 text-xs">*</span>
                </p>
                <div class="input-group input-group-sm border rounded-3 mb-3">
                    <input type="password" class="form-control border-0 rounded-3" placeholder="konfirmasi password" wire:model='password_form.konfirmasi_password_baru'>
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-between align-items-center">
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
                <button class="btn shadow-none btn-transparent rounded-pill text-capitalize" type="button" wire:click='nextStep'>
                    berikutnya
                    <i class="fa-solid fa-arrow-right text-sm ms-1"></i>
                </button>
            @endif
        </div>
    </div>

    <div wire:loading wire:target='prevStep, nextStep, saveNewPegawai'>
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-75 d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
        </div>
    </div>
</div>