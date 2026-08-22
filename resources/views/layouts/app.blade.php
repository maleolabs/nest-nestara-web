@include('layouts.header')
<body class="bg-ink-50 antialiased selection:bg-nest-100 selection:text-nest-900">
  <div id="app-shell" class="flex min-h-screen flex-col">
    <main class="flex-1">
      @yield('main')
    </main>
  </div>

  @livewireScripts
  <script src="{{ asset('assets/js/app.js') }}"></script>
  <script src="{{ asset('assets/js/livewire-event.js') }}"></script>
  @stack('scripts')
</body>
</html>
