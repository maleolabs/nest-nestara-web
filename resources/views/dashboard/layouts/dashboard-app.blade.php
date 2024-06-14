@include('layouts.header')
<body>
@include('dashboard.layouts.sidebar-grid')
    <main id="main" class="main">
        <div class="container-fluid mt-3">
            <div class="d-flex align-items-center mb-4">
                <div class="d-sm-none d-inline btn-left-side me-2">
                    <i class="bi bi-list dashboard-mobile-toggle"></i>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="app-text-primary">Dashboard</a></li>
                        @yield('breadcrumb-item')
                    </ol>
                </nav>
            </div>
            @yield('main')
        </div>
    </main>
</body>
@include('layouts.footer')