<button type="button" wire:click='showAll'
    class="mb-4 inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-4 py-2 text-xs font-bold text-ink-700 hover:border-nest-300 hover:bg-nest-50 transition"
    wire:loading.attr="disabled" wire:target='showAll'>
    <span wire:loading.remove wire:target='showAll'><i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali</span>
    <span wire:loading wire:target='showAll'><i class="fa-solid fa-spinner fa-spin"></i></span>
</button>

<div class="grid gap-6 lg:grid-cols-[240px_1fr]">
    {{-- foto --}}
    <div class="h-fit rounded-2xl border border-dashed border-ink-200 bg-ink-50/40 p-6 text-center">
        <img src="{{ $user_foto ? $user_foto->temporaryUrl() : asset('storage/users/' . $user_detail->foto) }}"
            alt="{{ $user_detail->pegawai->nama }}"
            class="mx-auto h-32 w-32 rounded-2xl object-cover ring-4 ring-white shadow-lg">
        <div class="mt-4 flex items-center justify-center gap-2 flex-wrap">
            <button type="button" @if($user_foto) wire:click='saveNewFoto' @else onclick="document.getElementById('userFoto').click()" @endif
                class="inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-xs font-bold transition {{ $user_foto ? 'bg-nest-700 text-white hover:bg-nest-800' : 'border border-ink-200 bg-white text-ink-700 hover:border-nest-300 hover:bg-nest-50' }}"
                wire:loading.attr="disabled" wire:target='saveNewFoto, user_foto'>
                <span wire:loading.remove wire:target='user_foto'><i class="fa-solid {{ $user_foto ? 'fa-upload' : 'fa-pencil' }} text-[10px]"></i> {{ $user_foto ? 'Simpan Foto' : 'Ubah Foto' }}</span>
                <span wire:loading wire:target='user_foto, saveNewFoto'><i class="fa-solid fa-spinner fa-spin"></i></span>
            </button>
            <input type="file" hidden id="userFoto" wire:model='user_foto'>
            @if (!$foto_is_default)
                <button type="button" wire:click='deleteFoto'
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-red-500 ring-1 ring-red-200 hover:bg-red-50 transition"
                    wire:loading.attr="disabled" wire:target='deleteFoto'>
                    <i class="fa-solid fa-trash text-xs" wire:loading.remove wire:target='deleteFoto'></i>
                    <i class="fa-solid fa-spinner fa-spin text-xs hidden" wire:loading wire:target='deleteFoto'></i>
                </button>
            @endif
        </div>
        <p class="mt-3 text-[11px] text-ink-400">Foto profil pegawai (maks 2MB)</p>
    </div>

    {{-- data --}}
    <div>
        {{-- data pegawai --}}
        <h3 class="text-[11px] font-extrabold uppercase tracking-widest text-ink-400">Data Pegawai</h3>
        <div class="mt-2 divide-y divide-ink-100">
            @foreach ([
                'nama' => 'Nama Pegawai',
                'jabatan' => 'Jabatan',
                'pangkat' => 'Pangkat',
                'nip' => 'NIP',
            ] as $field => $label)
                <div class="grid gap-2 py-4 sm:grid-cols-[180px_1fr] sm:items-center">
                    <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">{{ $label }}</p>
                    <div class="flex items-center gap-3">
                        <div class="min-w-0 flex-1">
                            <input type="text" wire:model.lazy='pegawai_form.{{ $field }}' placeholder="masukkan {{ strtolower($label) }}"
                                class="w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                            @error('pegawai_form.' . $field)
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='pegawai_form.{{ $field }}'></i>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="flex items-center gap-1.5 pb-4 text-xs text-ink-400">
            <i class="fa-solid fa-circle-info"></i> Tekan <kbd class="rounded-lg bg-white px-1.5 py-0.5 font-bold text-ink-600 ring-1 ring-ink-200">Enter</kbd> pada kolom untuk menyimpan.
        </p>

        {{-- data pengguna --}}
        <h3 class="mt-6 text-[11px] font-extrabold uppercase tracking-widest text-ink-400">Data Pengguna</h3>
        <div class="mt-2 divide-y divide-ink-100">
            @foreach ([
                'name' => 'Nama',
                'username' => 'Username',
                'email' => 'Email',
            ] as $field => $label)
                <div class="grid gap-2 py-4 sm:grid-cols-[180px_1fr] sm:items-center">
                    <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">{{ $label }}</p>
                    <div class="flex items-center gap-3">
                        <div class="min-w-0 flex-1">
                            <input type="text" wire:model.lazy='user_form.{{ $field }}' placeholder="masukkan {{ strtolower($label) }}"
                                class="w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                            @error('user_form.' . $field)
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='user_form.{{ $field }}'></i>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="flex items-center gap-1.5 text-xs text-ink-400">
            <i class="fa-solid fa-circle-info"></i> Tekan <kbd class="rounded-lg bg-white px-1.5 py-0.5 font-bold text-ink-600 ring-1 ring-ink-200">Enter</kbd> pada kolom untuk menyimpan.
        </p>

        {{-- password --}}
        <h3 class="mt-8 text-[11px] font-extrabold uppercase tracking-widest text-ink-400">Password</h3>
        @if ($password_saved)
            <div class="mt-3 flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 ring-1 ring-emerald-200">
                <i class="fa-solid fa-circle-check"></i> Password berhasil diperbaharui!
            </div>
        @endif
        <form wire:submit.prevent='savePassword' class="mt-3 max-w-md space-y-4">
            <div>
                <label class="text-xs font-bold tracking-wide text-ink-500 uppercase">Password Baru</label>
                <input type="password" wire:model.live='password_form.password_baru' placeholder="masukkan password baru"
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                @error('password_form.password_baru')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="text-xs font-bold tracking-wide text-ink-500 uppercase">Konfirmasi Password Baru</label>
                <input type="password" wire:model.live='password_form.konfirmasi_password_baru' placeholder="konfirmasi password baru"
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                @error('password_form.konfirmasi_password_baru')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if ($password_form->password_baru && $password_form->konfirmasi_password_baru)
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-extrabold text-white hover:bg-nest-800 transition disabled:opacity-50"
                    wire:loading.attr="disabled" wire:target='savePassword'>
                    <span wire:loading.remove wire:target='savePassword'><i class="fa-solid fa-floppy-disk text-xs"></i> Simpan Password</span>
                    <span wire:loading wire:target='savePassword'><i class="fa-solid fa-spinner fa-spin"></i></span>
                </button>
            @endif
        </form>
    </div>
</div>
