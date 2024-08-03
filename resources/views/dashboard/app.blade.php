@include('layouts.header')
<body class="bg-light">
    @include('dashboard.dashboard-nav')
    <main class="{{ str_contains(Route::currentRouteName(), 'dashboard') ? 'p-0 m-0' : 'container-fluid pt-4' }}">
        @yield('main')
    </main>
</body>
@include('layouts.footer')