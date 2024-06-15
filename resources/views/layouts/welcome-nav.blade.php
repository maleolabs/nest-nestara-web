<nav class="welcome-nav">
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('welcome') }}" class="text-decoration-none welcome-nav-logo">
                    <img src="{{ asset('storage/web/favicon.ico') }}" alt="icon">
                </a>
                <div class="d-sm-block d-none">
                    @yield('navbar-content')
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end gap-3">
                <a href="{{ route('jenis-perizinan') }}" class="welcome-nav-link {{ Route::currentRouteName() == 'jenis-perizinan' ? 'active' : '' }}">
                    Jenis Perizinan
                </a>
                @auth
                    <a href="javascript:;" class="welcome-nav-link">
                        Izin saya
                    </a>
                    <a href="javascript:;" class="ms-3 sidebar-btn-show position-relative">
                        <img src="{{ asset('storage/web/favicon.ico') }}" alt="" class="profile-img">
                    </a>
                @else
                    <a href="{{ route('login') }}" class="ms-3 welcome-nav-link">Login</a>
                    <a href="{{ route('register') }}">
                        <button class="btn btn-app-primary rounded-pill">
                            Register
                        </button>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>