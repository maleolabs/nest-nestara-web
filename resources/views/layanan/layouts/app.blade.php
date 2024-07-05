@include('layouts.header')
<body>
    @include('dashboard.dashboard-nav')
    <main id="layanan-main" class="main">
        @yield('main')
    </main>
</body>
@include('layouts.footer')