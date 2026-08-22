@include('layouts.header')
<body class="bg-ink-50 antialiased">
  <div id="app-shell" class="flex min-h-screen flex-col">
    @include('dashboard.dashboard-nav')
    <main id="layanan-main" class="mx-auto w-full max-w-[1280px] flex-1 px-4 sm:px-6 lg:px-8 pt-6">
      @yield('main')
    </main>
    @include('layouts.footer')
  </div>
</body>
</html>
