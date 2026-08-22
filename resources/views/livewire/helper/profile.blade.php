<div class="mx-auto max-w-[960px] px-4 sm:px-6 lg:px-8 py-6 space-y-6">
  {{-- header --}}
  <div>
    <h1 class="font-display font-extrabold text-[26px] leading-tight text-ink-900">Profil Saya</h1>
    <p class="mt-1 text-sm text-ink-600">Kelola foto, data pegawai, data akun, dan keamanan password. Perubahan tersimpan langsung.</p>
  </div>

  {{-- foto --}}
  <div class="rounded-2xl bg-white p-6 ring-1 ring-ink-200 shadow-[0_8px_24px_rgba(15,23,42,0.06)]">
    <div class="flex flex-col sm:flex-row gap-6">
      <div class="flex flex-col items-center">
        <div class="relative">
          <img src="{{ $user_foto ? $user_foto->temporaryUrl() : asset('storage/users/' . $user->foto) }}" alt="{{ $user->username }}" class="h-28 w-28 rounded-2xl object-cover ring-1 ring-ink-200 shadow-sm">
          <span class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-emerald-500 ring-2 ring-white grid place-items-center text-white text-[10px]"><i class="fa-solid fa-check"></i></span>
        </div>
        <div class="mt-4 flex items-center gap-2">
          <button class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-xs font-bold transition {{ $user_foto ? 'bg-nest-700 text-white hover:bg-nest-800' : 'bg-ink-900 text-white hover:bg-ink-800' }}" type="button" @if($user_foto) wire:click='saveNewFoto' @else onclick="document.getElementById('userFoto').click()" @endif wire:loading.class='opacity-50 pointer-events-none' wire:target='saveNewFoto, user_foto'>
            <span class="hidden" wire:loading wire:target='user_foto, saveNewFoto'><i class="fa-solid fa-spinner fa-spin"></i></span>
            <i class="fa-solid {{ $user_foto ? 'fa-upload' : 'fa-camera' }} text-xs" wire:loading.remove wire:target='user_foto'></i>
            <span>{{ $user_foto ? 'Simpan Foto' : 'Ubah Foto' }}</span>
          </button>
          <input type="file" hidden id="userFoto" wire:model='user_foto' accept="image/*">
          @if (!$foto_is_default)
            <button class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-red-50 text-red-600 ring-1 ring-red-200 hover:bg-red-100" type="button" wire:click='deleteFoto' wire:loading.class='opacity-50 pointer-events-none'>
              <i class="fa-solid fa-trash text-xs" wire:loading.remove wire:target='deleteFoto'></i>
              <i class="fa-solid fa-spinner fa-spin text-xs hidden" wire:loading wire:target='deleteFoto'></i>
            </button>
          @endif
        </div>
        <p class="mt-2 text-[11px] text-ink-500">JPG/PNG • Maks 2MB</p>
      </div>
      <div class="flex-1 min-w-0">
        <h3 class="font-bold text-ink-900">Foto Profil</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink-600">Gunakan foto jelas dan formal. Foto akan tampil di tanda tangan dan header. Klik “Ubah Foto” lalu “Simpan” untuk mengunggah.</p>
        <div class="mt-4 flex flex-wrap gap-2">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-nest-50 px-3 py-1 text-xs font-bold text-nest-700 ring-1 ring-nest-200"><i class="fa-solid fa-circle-check"></i> {{ $user->name }}</span>
          <span class="inline-flex items-center gap-1.5 rounded-full bg-ink-50 px-3 py-1 text-xs font-semibold text-ink-600 ring-1 ring-ink-200">{{ $user->email ?? $user->username }}</span>
        </div>
        @error('user_foto')<p class="mt-3 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
    </div>
  </div>

  @hasanyrole([FRONT_OFFICE_ROLE, BACK_OFFICE_ROLE])
  <div class="rounded-2xl bg-white p-6 ring-1 ring-ink-200 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <h3 class="font-bold text-ink-900">Data Pegawai</h3>
        <p class="text-sm text-ink-500">Informasi kepegawaian untuk penandatangan dan audit.</p>
      </div>
      <span class="hidden sm:inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200">Kepegawaian</span>
    </div>
    <div class="mt-5 grid gap-4 sm:grid-cols-2">
      <div>
        <label for="nama_pegawai" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nama Pegawai</label>
        <input type="text" id="nama_pegawai" wire:model='pegawai_form.nama' placeholder="Nama lengkap" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('pegawai_form.nama')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="jabatan" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Jabatan</label>
        <input type="text" id="jabatan" wire:model='pegawai_form.jabatan' placeholder="Contoh: Analis Perizinan" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('pegawai_form.jabatan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="pangkat" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Pangkat</label>
        <input type="text" id="pangkat" wire:model='pegawai_form.pangkat' placeholder="Contoh: Penata Muda" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('pegawai_form.pangkat')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="nip" class="text-xs font-bold tracking-wide text-ink-700 uppercase">NIP</label>
        <input type="text" id="nip" wire:model='pegawai_form.nip' placeholder="18 digit NIP" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('pegawai_form.nip')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
    </div>
    <div class="mt-5">
      <button class="inline-flex items-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-ink-800 transition disabled:opacity-50" wire:click='saveDataPegawai' wire:loading.class='opacity-50 pointer-events-none' wire:target='saveDataPegawai'>
        <span wire:loading.remove wire:target='saveDataPegawai'>Simpan Data Pegawai</span>
        <span class="hidden" wire:loading wire:target='saveDataPegawai'><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan…</span>
      </button>
    </div>
  </div>
  @endhasanyrole

  <div class="rounded-2xl bg-white p-6 ring-1 ring-ink-200 shadow-sm">
    <h3 class="font-bold text-ink-900">Data Pengguna</h3>
    <p class="text-sm text-ink-500">Akun login dan kontak. Email digunakan untuk verifikasi.</p>
    <div class="mt-5 grid gap-4 sm:grid-cols-2">
      <div>
        <label for="nama" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nama</label>
        <input type="text" id="nama" wire:model='user_form.name' class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('user_form.name')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="username" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Username</label>
        <input type="text" id="username" wire:model='user_form.username' class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('user_form.username')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
      <div class="sm:col-span-2">
        <label for="email" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Email</label>
        <input type="email" id="email" wire:model='user_form.email' class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('user_form.email')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
    </div>
    <div class="mt-5">
      <button class="inline-flex items-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-ink-800 transition" wire:click='saveDataPengguna' wire:loading.class='opacity-50 pointer-events-none' wire:target='saveDataPengguna'>
        <span wire:loading.remove wire:target='saveDataPengguna'>Simpan Akun</span>
        <span class="hidden" wire:loading wire:target='saveDataPengguna'><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan…</span>
      </button>
    </div>
  </div>

  <div class="rounded-2xl bg-white p-6 ring-1 ring-ink-200 shadow-sm">
    <h3 class="font-bold text-ink-900">Ubah Password</h3>
    <p class="text-sm text-ink-500">Ganti password secara berkala untuk keamanan.</p>
    <div class="mt-5 grid gap-4 sm:grid-cols-2">
      <div>
        <label for="password_sekarang" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Password Sekarang</label>
        <input type="password" id="password_sekarang" wire:model='password_form.password_lama' placeholder="••••••••" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('password_form.password_lama')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label for="password_baru" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Password Baru</label>
        <input type="password" id="password_baru" wire:model='password_form.password_baru' placeholder="Min. 8 karakter" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('password_form.password_baru')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
      <div class="sm:col-span-2">
        <label for="konfirmasi_password_baru" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Konfirmasi Password Baru</label>
        <input type="password" id="konfirmasi_password_baru" wire:model='password_form.konfirmasi_password_baru' placeholder="Ulangi password baru" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
        @error('password_form.konfirmasi_password_baru')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
      </div>
    </div>
    <div class="mt-5">
      <button class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-nest-800 transition" wire:click='savePassword' wire:loading.class='opacity-50 pointer-events-none' wire:target='savePassword'>
        <span wire:loading.remove wire:target='savePassword'>Simpan Password</span>
        <span class="hidden" wire:loading wire:target='savePassword'><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan…</span>
      </button>
    </div>
  </div>
</div>
