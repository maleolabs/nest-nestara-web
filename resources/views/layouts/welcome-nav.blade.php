@php
  $isPemohon = auth()->check() && auth()->user()->hasRole('pemohon');
@endphp
<nav class="sticky top-0 z-50 border-b border-ink-200/60 bg-white/80 backdrop-blur-xl supports-[backdrop-filter]:bg-white/70">
  <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
    <div class="flex h-[64px] items-center justify-between gap-4">
      {{-- brand --}}
      <div class="flex items-center gap-3 min-w-0">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
          <div class="h-9 w-9 rounded-xl bg-nest-700 flex items-center justify-center shadow-sm ring-1 ring-nest-800/10 group-hover:bg-nest-800 transition">
            <img src="{{ asset('storage/web/favicon.ico') }}" alt="{{ Version::productName() }}" class="h-6 w-6 object-contain rounded-md">
          </div>
          <div class="hidden sm:block leading-none">
            <p class="font-display font-extrabold tracking-tight text-[15px] text-ink-900">{{ strtoupper(Version::productName()) }}</p>
            <p class="text-[11px] font-medium tracking-widest text-ink-500 uppercase -mt-0.5">{{ Version::organizationName() }}</p>
          </div>
        </a>
        <div class="hidden lg:flex items-center gap-1 ml-6">
          <span class="h-4 w-px bg-ink-200 mx-2"></span>
          @yield('navbar-content')
        </div>
      </div>

      {{-- desktop nav --}}
      <div class="hidden md:flex items-center gap-1">
        <a href="{{ route('jenis-perizinan') }}" class="px-3 py-2 rounded-full text-sm font-semibold transition {{ Route::currentRouteName() == 'jenis-perizinan' ? 'bg-ink-900 text-white' : 'text-ink-700 hover:bg-ink-100' }}">
          Jenis Perizinan
        </a>
        @auth
          @role('pemohon')
            <a href="{{ route('user.izin') }}" class="px-3 py-2 rounded-full text-sm font-semibold transition {{ Route::currentRouteName() == 'user.izin' ? 'bg-ink-900 text-white' : 'text-ink-700 hover:bg-ink-100' }}">
              Izin Saya
            </a>
          @endrole
          @role('pemohon')
            <a href="{{ route('create-izin') }}" class="hidden lg:inline-flex items-center gap-2 ml-2 px-4 py-2 rounded-full bg-nest-700 text-white text-sm font-bold shadow-sm hover:bg-nest-800 transition">
              <i class="fa-solid fa-plus text-xs"></i> Buat Izin
            </a>
          @endrole
          <button type="button" class="sidebar-btn-show ml-2 flex items-center gap-2 rounded-full border border-ink-200 bg-white pl-1 pr-3 py-1 hover:border-ink-300 transition">
            <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="avatar" class="h-7 w-7 rounded-full object-cover">
            <span class="hidden sm:block text-sm font-semibold text-ink-800 max-w-[140px] truncate">{{ Str::limit(auth()->user()->name, 14) }}</span>
            <i class="fa-solid fa-chevron-down text-[10px] text-ink-400"></i>
          </button>
        @else
          <a href="{{ route('login') }}" class="px-4 py-2 rounded-full text-sm font-semibold text-ink-700 hover:bg-ink-100 transition">Masuk</a>
          <a href="{{ route('register') }}" class="ml-1 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-nest-700 text-white text-sm font-bold shadow-[0_8px_20px_rgba(15,118,110,0.25)] hover:bg-nest-800 hover:shadow-[0_10px_24px_rgba(15,118,110,0.32)] transition">
            Daftar Akun <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        @endauth
      </div>

      {{-- mobile toggle --}}
      <button id="mobileMenuBtn" aria-expanded="false" aria-controls="mobileMenu" class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-700">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>
  </div>

  {{-- mobile menu --}}
  <div id="mobileMenu" class="hidden md:hidden border-t border-ink-200 bg-white">
    <div class="px-4 py-4 space-y-2">
      <a href="{{ route('jenis-perizinan') }}" class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold {{ Route::currentRouteName() == 'jenis-perizinan' ? 'bg-ink-900 text-white' : 'bg-ink-50 text-ink-800' }}">
        Jenis Perizinan <i class="fa-solid fa-chevron-right text-xs opacity-60"></i>
      </a>
      @auth
        @role('pemohon')
          <a href="{{ route('create-izin') }}" class="flex items-center justify-between rounded-xl bg-nest-700 px-4 py-3 text-sm font-bold text-white">
            Buat Izin Baru <i class="fa-solid fa-plus"></i>
          </a>
        @endrole
        @role('pemohon')
          <a href="{{ route('user.izin') }}" class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold bg-ink-50 text-ink-800">Izin Saya <i class="fa-solid fa-file-lines"></i></a>
        @endrole
      @else
        <div class="grid grid-cols-2 gap-2 pt-2">
          <a href="{{ route('login') }}" class="rounded-full border border-ink-200 bg-white py-3 text-center text-sm font-bold text-ink-800">Masuk</a>
          <a href="{{ route('register') }}" class="rounded-full bg-nest-700 py-3 text-center text-sm font-bold text-white">Daftar</a>
        </div>
      @endauth
    </div>
  </div>
</nav>

{{-- bottom tab bar for pemohon (mobile) --}}
@auth
  <div class="md:hidden fixed bottom-0 inset-x-0 z-40 border-t border-ink-200 bg-white/95 backdrop-blur supports-[backdrop-filter]:bg-white/80">
    <div class="grid grid-cols-4 gap-1 px-2 py-2">
      <a href="{{ route('welcome') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 {{ Route::currentRouteName() == 'welcome' ? 'text-nest-700 bg-nest-50' : 'text-ink-500' }}">
        <i class="fa-solid fa-house text-[16px]"></i><span class="text-[10px] font-bold tracking-wide">Beranda</span>
      </a>
      @role('pemohon')
        <a href="{{ route('create-izin') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 {{ Route::currentRouteName() == 'create-izin' ? 'text-nest-700 bg-nest-50' : 'text-ink-500' }}">
          <i class="fa-solid fa-file-circle-plus text-[16px]"></i><span class="text-[10px] font-bold tracking-wide">Buat Izin</span>
        </a>
      @endrole
      <a href="{{ route('jenis-perizinan') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 {{ Route::currentRouteName() == 'jenis-perizinan' ? 'text-nest-700 bg-nest-50' : 'text-ink-500' }}">
        <i class="fa-solid fa-layer-group text-[16px]"></i><span class="text-[10px] font-bold tracking-wide">Jenis</span>
      </a>
      @role('pemohon')
        <a href="{{ route('user.izin') }}" class="flex flex-col items-center gap-1 rounded-xl py-2 {{ Route::currentRouteName() == 'user.izin' ? 'text-nest-700 bg-nest-50' : 'text-ink-500' }}">
          <i class="fa-solid fa-clipboard-list text-[16px]"></i><span class="text-[10px] font-bold tracking-wide">Izin Saya</span>
        </a>
      @endrole
    </div>
  </div>
@endauth
