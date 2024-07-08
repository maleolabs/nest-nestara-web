@include('layouts.header')
<body class="bg-light">
    @include('dashboard.dashboard-nav')
    <main id="layanan-main" class="main">
        @yield('main')
    </main>
</body>
@include('layouts.footer')