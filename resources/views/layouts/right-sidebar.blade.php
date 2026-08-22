<div id="sidebarContainer" class="hidden fixed inset-0 z-[60]">
  <div id="sidebarBackdrop" class="absolute inset-0 bg-ink-900/10 backdrop-blur-[1px]"></div>
  <div class="absolute right-3 sm:right-4 top-[72px] w-[360px] max-w-[92vw] rounded-2xl bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)] ring-1 ring-ink-200 overflow-hidden animate-fadeIn flex flex-col max-h-[80vh]">
    <div class="p-5 border-b border-ink-100 flex items-start justify-between gap-4">
      <div class="flex gap-3 min-w-0">
        <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="avatar" class="h-11 w-11 rounded-full object-cover ring-2 ring-nest-100">
        <div class="min-w-0">
          <p class="text-sm font-bold text-ink-900 leading-tight truncate">{{ auth()->user()->name }}</p>
          <p class="text-xs text-ink-500 truncate">{{ auth()->user()->email ?? auth()->user()->username }}</p>
          <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-nest-50 px-2 py-0.5 text-[11px] font-bold tracking-wide text-nest-700 ring-1 ring-nest-200">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            {{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}
          </span>
        </div>
      </div>
      <button class="sidebar-btn-close inline-flex h-8 w-8 items-center justify-center rounded-full bg-ink-50 text-ink-600 hover:bg-ink-100 transition ring-1 ring-ink-200">
        <i class="fa-solid fa-xmark text-xs"></i>
      </button>
    </div>

    <nav class="flex-1 overflow-y-auto p-3 space-y-1">
      @unlessrole('pemohon')
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ str_contains(Route::currentRouteName(), 'dashboard') ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
          <span class="h-8 w-8 grid place-items-center rounded-lg {{ str_contains(Route::currentRouteName(), 'dashboard') ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-table-columns text-sm"></i></span>
          Dashboard
        </a>
      @endunlessrole
      <a href="{{ route('profile') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold {{ str_contains(Route::currentRouteName(), 'profile') ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
        <span class="h-8 w-8 grid place-items-center rounded-lg {{ str_contains(Route::currentRouteName(), 'profile') ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid fa-user"></i></span>
        Profil Saya
      </a>
      <a href="{{ route('jenis-perizinan') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-ink-50">
        <span class="h-8 w-8 grid place-items-center rounded-lg bg-ink-100"><i class="fa-solid fa-layer-group"></i></span>
        Katalog Perizinan
      </a>
      @role('pemohon')
        <a href="{{ route('user.izin') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-ink-50">
          <span class="h-8 w-8 grid place-items-center rounded-lg bg-ink-100"><i class="fa-solid fa-clipboard-list"></i></span>
          Izin Saya
        </a>
      @endrole
    </nav>

    <div class="p-4 border-t border-ink-100 space-y-3 bg-ink-50/30">
      <div class="rounded-2xl bg-gradient-to-br from-nest-700 to-nest-800 p-4 text-white">
        <p class="text-sm font-bold">Butuh bantuan?</p>
        <p class="text-xs text-white/80 mt-1">Hubungi PMPTSP Bone Bolango jam 08.00–16.00 WITA</p>
        <a href="mailto:support@perizinanpmptsp.go.id" class="mt-3 inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-bold text-nest-800">Hubungi Support <i class="fa-solid fa-envelope"></i></a>
      </div>
      <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-bold text-ink-700 hover:bg-ink-50 transition">
          <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </button>
      </form>
      <p class="text-center text-xs text-ink-500">Tekan di luar atau Esc untuk tutup</p>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('sidebarContainer');
    const backdrop = document.getElementById('sidebarBackdrop');
    const opens = document.querySelectorAll('.sidebar-btn-show');
    const closes = document.querySelectorAll('.sidebar-btn-close');
    const show = (e) => {
      e?.preventDefault();
      container?.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    };
    const hide = () => {
      container?.classList.add('hidden');
      document.body.style.overflow = '';
    };
    opens.forEach(b=>b.addEventListener('click', show));
    closes.forEach(b=>b.addEventListener('click', hide));
    backdrop?.addEventListener('click', hide);
    document.addEventListener('keydown', e=> { if(e.key==='Escape') hide(); });
  });
</script>
