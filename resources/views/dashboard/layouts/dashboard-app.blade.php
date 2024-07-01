@include('layouts.header')
<body>
@include('dashboard.layouts.sidebar-grid')
    <main id="main" class="main">
        <div class="container-fluid mt-3">
            <div class="d-flex flex-wrap align-items-start justify-content-between mb-2">
                <div class="d-sm-none d-inline btn-left-side me-2">
                    <i class="fa-solid fa-bars dashboard-mobile-toggle"></i>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="javascript:;">
                                @auth
                                    {{ auth()->user()->name }}
                                @else
                                    Guest
                                @endauth
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        @stack('breadcrumb-item')
                    </ol>
                </nav>
                <div class="d-flex align-items-center flex-wrap gap-3">
                    @stack('nav-dashboard-content')
                    <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="" class="img-circle img-circle-sm">
                </div>
            </div>
            @yield('main')
        </div>
    </main>
</body>
@include('layouts.footer')