<nav class="space-y-6">
  @role(FRONT_OFFICE_ROLE)
    <div>
      <p class="px-3 text-[11px] font-extrabold tracking-widest text-ink-500 uppercase">Layanan</p>
      <div class="mt-2 space-y-1">
        <a href="{{ route('layanan.pendaftaran') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'layanan.pendaftaran' ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Route::currentRouteName() == 'layanan.pendaftaran' ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-book-open text-xs"></i></span>
          <span class="flex-1">Pendaftaran</span>
          <span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ countByProcess('pendaftaran') }}</span>
        </a>
        <a href="{{ route('layanan.penyerahan') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'layanan.penyerahan' ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Route::currentRouteName() == 'layanan.penyerahan' ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-file-export text-xs"></i></span>
          <span class="flex-1">Penyerahan</span>
          <span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ countByProcess('penyerahan') }}</span>
        </a>
      </div>
    </div>
  @elserole(BACK_OFFICE_ROLE)
    <div>
      <p class="px-3 text-[11px] font-extrabold tracking-widest text-ink-500 uppercase">Back Office</p>
      <div class="mt-2 space-y-1">
        <a href="{{ route('pegawai.pendataan') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'pegawai.pendataan' ? 'bg-nest-700 text-white' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Route::currentRouteName() == 'pegawai.pendataan' ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-file-signature text-xs"></i></span>
          <span class="flex-1">Pendataan</span><span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ countByProcess('pendataan') }}</span>
        </a>
        <a href="{{ route('pegawai.rekomendasi') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'pegawai.rekomendasi' ? 'bg-nest-700 text-white' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Route::currentRouteName() == 'pegawai.rekomendasi' ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-medal text-xs"></i></span>
          <span class="flex-1">Rekomendasi</span><span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ countByProcess('rekomendasi') }}</span>
        </a>
        <a href="{{ route('pegawai.penetapan') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'pegawai.penetapan' ? 'bg-nest-700 text-white' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Route::currentRouteName() == 'pegawai.penetapan' ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-file-circle-check text-xs"></i></span>
          <span class="flex-1">Penetapan</span><span class="rounded-full bg-red-500 px-2 py-0.5 text-xs font-bold text-white">{{ countByProcess('penetapan') }}</span>
        </a>
      </div>
    </div>
  @elserole(ADMIN_ROLE)
    <div>
      <p class="px-3 text-[11px] font-extrabold tracking-widest text-ink-500 uppercase">Administrasi</p>
      <div class="mt-2 space-y-1">
        <a href="{{ route('admin.jenis-perizinan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ Str::contains(Route::currentRouteName(), 'jenis-perizinan') ? 'bg-nest-700 text-white' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Str::contains(Route::currentRouteName(), 'jenis-perizinan') ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-file-invoice text-xs"></i></span> Jenis Perizinan
        </a>
        <a href="{{ route('admin.pegawai') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ Route::currentRouteName() == 'admin.pegawai' ? 'bg-nest-700 text-white' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Route::currentRouteName() == 'admin.pegawai' ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-users-line text-xs"></i></span> Pegawai
        </a>
        <a href="{{ route('admin.pengaturan') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ Route::currentRouteName() == 'admin.pengaturan' ? 'bg-nest-700 text-white' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ Route::currentRouteName() == 'admin.pengaturan' ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-gear text-xs"></i></span> Pengaturan
        </a>
      </div>
    </div>
  @endrole

  @hasanyrole([FRONT_OFFICE_ROLE, BACK_OFFICE_ROLE])
    <div class="pt-4 border-t border-ink-100">
      @livewire('dashboard.component.left-menu-content')
    </div>
  @endhasanyrole
</nav>
