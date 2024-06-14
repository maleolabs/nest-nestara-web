@include('layouts.header')
<body>
@include('dashboard.layouts.sidebar-grid')
    <md-circular-progress value="0.75"></md-circular-progress>
    <main id="main" class="main">
        <div class="container-fluid mt-3">
            <div class="d-flex align-items-center mb-2">
                <div class="d-sm-none d-inline btn-left-side me-2">
                    <i class="fa-solid fa-bars dashboard-mobile-toggle"></i>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="app-text-primary">Dashboard</a></li>
                        @stack('breadcrumb-item')
                    </ol>
                </nav>
                <div class="ms-auto d-flex gap-3 align-items-center justify-content-end">
                    <div class="d-sm-block d-none">
                        <h6 class="mb-0">Marij Mokoginta</h6>
                        <small class="text-muted">administrator</small>
                    </div>
                    <img src="{{ asset('storage/users/profile.png') }}" alt="" class="img-circle img-circle-sm">
                </div>
            </div>
            @yield('main')
        </div>
    </main>
</body>
@include('layouts.footer')