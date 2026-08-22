<header class="sticky top-0 z-30 border-b border-ink-200 bg-white/80 backdrop-blur-xl">
  <div class="flex h-[64px] items-center justify-between gap-4 px-4 sm:px-6">
    <div class="flex items-center gap-3 min-w-0">
      <button type="button" class="btn-show-left-menu inline-flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-700 hover:bg-ink-50 transition" aria-label="Buka menu pegawai">
        <i class="fa-solid fa-bars"></i>
      </button>
      <a href="{{ route('welcome') }}" class="hidden sm:flex items-center gap-2">
        <div class="h-8 w-8 rounded-lg bg-white ring-1 ring-ink-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-6 w-6 object-contain"></div>
        <span class="font-display font-extrabold text-sm tracking-tight text-ink-900">NESTARA</span>
      </a>
      <nav aria-label="Breadcrumb" class="hidden md:flex items-center gap-1 text-sm">
        <span class="h-4 w-px bg-ink-200 mx-1"></span>
        <a href="{{ route('profile') }}" class="inline-flex items-center gap-1.5 rounded-full bg-ink-50 px-3 py-1 text-xs font-bold text-ink-700 ring-1 ring-ink-200">
          <i class="fa-solid fa-at text-ink-400"></i> {{ auth()->user()->username ?? 'Guest' }}
        </a>
        @if (str_contains(Route::currentRouteName(), 'dashboard'))
          <span class="text-ink-300">/</span>
          <span class="text-xs font-semibold text-ink-600">Dashboard</span>
        @endif
        @stack('breadcrumb-item')
      </nav>
    </div>

    <div class="flex items-center gap-2">
      @stack('nav-dashboard-content')
      <button type="button" class="sidebar-btn-show flex items-center gap-2 rounded-full border border-ink-200 bg-white pl-1 pr-3 py-1 hover:border-ink-300 transition">
        <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="avatar" class="h-7 w-7 rounded-full object-cover">
        <span class="hidden sm:block text-sm font-semibold text-ink-800 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
        <i class="fa-solid fa-chevron-down text-[10px] text-ink-400"></i>
      </button>
    </div>
  </div>
</header>
@include('layouts.right-sidebar')
@include('layouts.left-menu')
