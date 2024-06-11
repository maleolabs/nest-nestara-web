<nav class="welcome-nav">
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="welcome-nav-logo">
                <img src="{{ asset('storage/web/favicon.ico') }}" alt="icon">
            </div>
            <div class="d-flex align-items-center justify-content-end gap-3">
                @auth
                    <a href="#" class="nav-link">
                        Jenis Perizinan
                    </a>
                    <a href="#" class="nav-link">
                        Izin saya
                    </a>
                    <a href="#" class="ms-3 sidebar-btn-show position-relative">
                        <img src="{{ asset('storage/web/favicon.ico') }}" alt="" class="profile-img">
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-decoration-none text-dark btn-edit">Login</a>
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