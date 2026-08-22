<div class="relative flex h-full flex-col">
    {{-- header + step title --}}
    <div class="text-center">
        <h2 class="font-display font-extrabold text-[18px] text-ink-900">Buat Data Pegawai Baru</h2>
        @if ($step != self::RESULT_STEP)
            <p class="mt-1 inline-flex items-center gap-2 rounded-full bg-nest-50 px-3.5 py-1.5 text-xs font-extrabold uppercase tracking-wide text-nest-700 ring-1 ring-nest-200">
                {{ match($step) {
                    self::ROLE_STEP => 'Pilih Peran',
                    self::PEGAWAI_STEP => 'Data Pegawai',
                    self::USER_STEP => 'Data Akun',
                    self::PASSWORD_STEP => 'Keamanan',
                    default => str_capitalize($step),
                } }}
            </p>
        @endif
    </div>

    <div class="my-8 flex-1">
        {{-- step: role --}}
        <div class="{{ $step == self::ROLE_STEP ? 'grid gap-4 sm:grid-cols-3' : 'hidden' }} mx-auto max-w-2xl">
            @foreach ([
                FRONT_OFFICE_ROLE => ['Front Office', 'fa-headset', 'bg-sky-50 text-sky-600', 'Melayani pendaftaran & layanan izin di loket.'],
                BACK_OFFICE_ROLE => ['Back Office', 'fa-briefcase', 'bg-violet-50 text-violet-600', 'Memproses & mengelola berkas permohonan.'],
                PENANDATANGAN => ['Penandatangan', 'fa-signature', 'bg-amber-50 text-amber-600', 'Menandatangani dokumen perizinan.'],
            ] as $roleValue => [$roleLabel, $icon, $iconClass, $desc])
                <div>
                    <input type="radio" name="role" wire:model='role' value="{{ $roleValue }}" id="role-{{ $loop->index }}" class="peer sr-only" autocomplete="off">
                    <label for="role-{{ $loop->index }}"
                        class="flex h-full cursor-pointer flex-col items-center gap-3 rounded-2xl border border-ink-200 bg-white p-6 text-center transition hover:border-nest-300 hover:bg-nest-50/50 peer-checked:border-nest-600 peer-checked:bg-nest-50 peer-checked:ring-4 peer-checked:ring-nest-100">
                        <span class="grid h-12 w-12 place-items-center rounded-xl {{ $iconClass }}">
                            <i class="fa-solid {{ $icon }}"></i>
                        </span>
                        <span class="text-sm font-extrabold text-ink-900">{{ $roleLabel }}</span>
                        <span class="text-[11px] leading-snug text-ink-500">{{ $desc }}</span>
                    </label>
                </div>
            @endforeach
        </div>

        {{-- step: data pegawai --}}
        <div class="{{ $step == self::PEGAWAI_STEP ? 'block' : 'hidden' }} mx-auto max-w-lg space-y-5">
            @foreach ([
                'nama' => ['Nama Pegawai', true],
                'jabatan' => ['Jabatan', true],
                'pangkat' => ['Pangkat', false],
                'nip' => ['NIP', true],
            ] as $field => [$label, $required])
                <div>
                    <label class="text-xs font-bold tracking-wide text-ink-500 uppercase">{{ $label }} @if($required)<span class="text-red-500">*</span>@endif</label>
                    <input type="text" wire:model='new_pegawai_form.{{ $field }}' placeholder="masukkan {{ strtolower($label) }}"
                        class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                    @error('new_pegawai_form.' . $field)
                        <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        {{-- step: user --}}
        <div class="{{ $step == self::USER_STEP ? 'block' : 'hidden' }} mx-auto max-w-lg space-y-5">
            <div>
                <label class="text-xs font-bold tracking-wide text-ink-500 uppercase">Username <span class="text-red-500">*</span></label>
                <input type="text" wire:model='new_user_form.username' placeholder="masukkan username"
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                @error('new_user_form.username')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-bold tracking-wide text-ink-500 uppercase">Email</label>
                <input type="email" wire:model='new_user_form.email' placeholder="masukkan email"
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                @error('new_user_form.email')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- step: password --}}
        <div class="{{ $step == self::PASSWORD_STEP ? 'block' : 'hidden' }} mx-auto max-w-lg space-y-5">
            <div>
                <label class="text-xs font-bold tracking-wide text-ink-500 uppercase">Password <span class="text-red-500">*</span></label>
                <input type="password" wire:model='new_password_form.password_baru' placeholder="masukkan password"
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                @error('new_password_form.password_baru')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-bold tracking-wide text-ink-500 uppercase">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" wire:model='new_password_form.konfirmasi_password_baru' placeholder="konfirmasi password"
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                @error('new_password_form.konfirmasi_password_baru')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- step: result --}}
        <div class="{{ $step == self::RESULT_STEP ? 'flex flex-col items-center gap-4 py-10 text-center' : 'hidden' }}">
            <span class="h-16 w-16 grid place-items-center rounded-full text-2xl {{ $success ? 'bg-emerald-50 text-emerald-600 ring-8 ring-emerald-50/60' : 'bg-red-50 text-red-500 ring-8 ring-red-50/60' }}">
                <i class="fa-solid {{ $success ? 'fa-check' : 'fa-xmark' }}"></i>
            </span>
            <p class="max-w-sm text-sm font-semibold text-ink-600">{{ $result_message }}</p>
            <button type="button" wire:click='createNew'
                class="mt-2 inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-extrabold text-white hover:bg-nest-800 transition"
                wire:loading.attr="disabled" wire:target='createNew'>
                <span wire:loading.remove wire:target='createNew'><i class="fa-solid fa-user-plus text-xs"></i> Buat Data Pegawai Baru</span>
                <span wire:loading wire:target='createNew'><i class="fa-solid fa-spinner fa-spin"></i></span>
            </button>
        </div>
    </div>

    {{-- nav --}}
    <div class="flex items-center justify-between gap-3">
        @if ($step != self::RESULT_STEP)
            @if ($step != self::ROLE_STEP)
                <button type="button" wire:click='prevStep'
                    class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-4 py-2 text-xs font-bold text-ink-700 hover:bg-ink-50 transition"
                    wire:loading.attr="disabled" wire:target='prevStep'>
                    <i class="fa-solid fa-arrow-left text-[10px]"></i> Sebelumnya
                </button>
            @else
                <span></span>
            @endif

            <div class="ml-auto">
                @if ($step == self::PASSWORD_STEP || $role == PENANDATANGAN)
                    <button type="button" wire:click='saveNewPegawai'
                        class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-6 py-2.5 text-sm font-extrabold text-white hover:bg-nest-800 transition disabled:opacity-50"
                        wire:loading.attr="disabled" wire:target='saveNewPegawai'>
                        <span wire:loading.remove wire:target='saveNewPegawai'><i class="fa-solid fa-floppy-disk text-xs"></i> Simpan</span>
                        <span wire:loading wire:target='saveNewPegawai'><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                @else
                    <button type="button" wire:click='nextStep'
                        class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-6 py-2.5 text-sm font-extrabold text-white hover:bg-nest-800 transition disabled:opacity-50"
                        wire:loading.attr="disabled" wire:target='nextStep'>
                        <span wire:loading.remove wire:target='nextStep'>Berikutnya <i class="fa-solid fa-arrow-right text-[10px]"></i></span>
                        <span wire:loading wire:target='nextStep'><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- loading overlay --}}
    <div wire:loading wire:target='prevStep, nextStep, saveNewPegawai, createNew'>
        <div class="absolute inset-0 z-10 grid place-items-center rounded-2xl bg-white/75 backdrop-blur-[2px]">
            <div class="flex flex-col items-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-xl text-nest-700"></i>
                <p class="text-xs font-bold text-ink-500">Memproses…</p>
            </div>
        </div>
    </div>
</div>
