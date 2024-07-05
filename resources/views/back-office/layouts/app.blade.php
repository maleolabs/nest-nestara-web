@include('layouts.header')
<body>
    @include('dashboard.dashboard-nav')
    <main id="back-office-main" class="main">
        <div class="container-fluid">
            @yield('main')
        </div>
    </main>
</body>
@include('layouts.footer')