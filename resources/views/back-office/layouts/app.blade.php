@include('layouts.header')
<body>
    @include('dashboard.dashboard-nav')
    <main id="back-office-main" class="main">
        <div class="bg-light">
            @yield('main')
        </div>
    </main>
</body>
@include('layouts.footer')