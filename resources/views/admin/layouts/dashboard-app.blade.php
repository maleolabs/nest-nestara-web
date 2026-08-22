@include('layouts.header')
<body class="bg-ink-50 antialiased">
  @include('admin.layouts.sidebar-grid')
  <div id="app-shell" class="flex min-h-screen flex-col lg:pl-[280px]">
    <main id="main" class="flex-1">
      <div class="sticky top-0 z-10 border-b border-ink-200 bg-white/80 backdrop-blur px-4 sm:px-6 lg:px-8 py-3 flex flex-wrap items-center justify-between gap-3">
        <nav aria-label="breadcrumb" class="text-sm">
          <ol class="flex items-center gap-2">
            <li><span class="inline-flex items-center gap-2 rounded-full bg-ink-900 px-3 py-1 text-xs font-bold text-white"><i class="fa-solid fa-user"></i> {{ auth()->user()->name }}</span></li>
            @stack('breadcrumb-item')
          </ol>
        </nav>
        <div class="flex items-center gap-2">
          @stack('nav-dashboard-content')
          <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="" class="h-8 w-8 rounded-full object-cover ring-1 ring-ink-200">
        </div>
      </div>
      <div class="px-4 sm:px-6 lg:px-8 pt-6">
        @yield('main')
      </div>
    </main>
    @include('layouts.footer')
  </div>
</body>
</html>
