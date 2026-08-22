<aside id="sidebar" class="hidden lg:flex fixed top-0 left-0 bottom-0 w-[280px] bg-ink-900 text-white flex-col border-r border-white/10">
  <div class="p-6">
    <div class="flex items-center gap-3">
      <div class="h-9 w-9 rounded-xl bg-white grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-7 w-7 object-contain"></div>
      <div>
        <p class="font-display font-extrabold tracking-tight leading-none">{{ strtoupper(Version::productName()) }}</p>
        <p class="text-[11px] tracking-widest text-white/60 uppercase">Admin Console</p>
      </div>
    </div>
  </div>

  <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
    <div>
      <p class="px-3 text-[11px] font-extrabold tracking-widest text-white/50 uppercase">Menu</p>
      <div class="mt-2 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'admin.dashboard' ? 'bg-white text-ink-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
          <i class="fa-solid fa-table-columns w-5"></i> Dashboard
        </a>
        <a href="{{ route('admin.jenis-perizinan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Str::contains(Route::currentRouteName(), 'jenis-perizinan') ? 'bg-white text-ink-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
          <i class="fa-solid fa-file-invoice w-5"></i> Jenis Perizinan
        </a>
        <a href="{{ route('admin.pemantauan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'admin.pemantauan.index' ? 'bg-white text-ink-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
          <i class="fa-solid fa-chart-simple w-5"></i> Pemantauan
        </a>
        <a href="{{ route('admin.reporting.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'admin.reporting.index' ? 'bg-white text-ink-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
          <i class="fa-solid fa-file-lines w-5"></i> Reporting
        </a>
      </div>
    </div>

    <div>
      <p class="px-3 text-[11px] font-extrabold tracking-widest text-white/50 uppercase">System</p>
      <div class="mt-2 space-y-1">
        <a href="{{ route('admin.pegawai') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'admin.pegawai' ? 'bg-white text-ink-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
          <i class="fa-solid fa-users-gear w-5"></i> Pegawai
        </a>
        <a href="{{ route('admin.pengaturan') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ Route::currentRouteName() == 'admin.pengaturan' ? 'bg-white text-ink-900' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
          <i class="fa-solid fa-gear w-5"></i> Pengaturan
        </a>
        <a href="{{ route('profile.admin') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-white/70 hover:bg-white/10 hover:text-white">
          <i class="fa-solid fa-user w-5"></i> Profil
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-white/70 hover:bg-white/10 hover:text-white">
            <i class="fa-solid fa-power-off w-5"></i> Keluar
          </button>
        </form>
      </div>
    </div>
  </nav>

  <div class="p-4 border-t border-white/10">
    <div class="rounded-2xl bg-white/10 p-3 ring-1 ring-white/15">
      <p class="text-xs font-bold">Butuh bantuan?</p>
      <p class="text-xs text-white/60">Dokumentasi & SOP tersedia di pusat bantuan.</p>
    </div>
    <div class="mt-3 flex items-center gap-3">
      <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="" class="h-8 w-8 rounded-full object-cover ring-1 ring-white/20">
      <div class="min-w-0">
        <p class="text-sm font-bold leading-none truncate">{{ auth()->user()->name }}</p>
        <p class="text-xs text-white/60 truncate">{{ auth()->user()->email }}</p>
      </div>
    </div>
  </div>
</aside>

{{-- mobile topbar for admin --}}
<div class="lg:hidden sticky top-0 z-30 border-b border-ink-200 bg-white/90 backdrop-blur px-4 py-3 flex items-center justify-between">
  <div class="flex items-center gap-3">
    <div class="h-8 w-8 rounded-lg bg-white ring-1 ring-ink-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-6 w-6 object-contain"></div>
    <span class="font-display font-extrabold text-sm">{{ strtoupper(Version::productName()) }} Admin</span>
  </div>
  <button onclick="document.getElementById('mobileAdminMenu').classList.toggle('hidden')" class="h-9 w-9 rounded-full border border-ink-200 grid place-items-center"><i class="fa-solid fa-bars"></i></button>
</div>
<div id="mobileAdminMenu" class="lg:hidden hidden border-b border-ink-200 bg-white px-4 py-3 space-y-1">
  <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold {{ Route::currentRouteName() == 'admin.dashboard' ? 'bg-ink-900 text-white' : 'bg-ink-50' }}"><i class="fa-solid fa-table-columns"></i> Dashboard</a>
  <a href="{{ route('admin.jenis-perizinan.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold {{ Str::contains(Route::currentRouteName(), 'jenis-perizinan') ? 'bg-ink-900 text-white' : 'bg-ink-50' }}"><i class="fa-solid fa-file-invoice"></i> Jenis Perizinan</a>
  <a href="{{ route('admin.pegawai') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold {{ Route::currentRouteName() == 'admin.pegawai' ? 'bg-ink-900 text-white' : 'bg-ink-50' }}"><i class="fa-solid fa-users-gear"></i> Pegawai</a>
  <a href="{{ route('admin.pengaturan') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold {{ Route::currentRouteName() == 'admin.pengaturan' ? 'bg-ink-900 text-white' : 'bg-ink-50' }}"><i class="fa-solid fa-gear"></i> Pengaturan</a>
</div>
