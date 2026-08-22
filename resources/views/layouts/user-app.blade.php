@include('layouts.header')
@auth
  @include('layouts.right-sidebar')
@endauth
<body class="{{ Route::currentRouteName() != 'welcome' ? 'bg-ink-50' : 'bg-white' }} antialiased selection:bg-nest-100 selection:text-nest-900">
  <div id="app-shell" class="flex min-h-screen flex-col">
    @include('layouts.welcome-nav')
    <main class="flex-1">
      @yield('main')
    </main>
    @include('layouts.footer')
    {{-- clearance untuk bottom tab bar (mobile) --}}
    <div class="h-[72px] md:hidden"></div>
  </div>
</body>
</html>
