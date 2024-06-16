<div class="side-sm-fixed-container d-sm-inline d-none w-sm-100">
    <aside id="sidebar" class="sidebar side-sm-fixed">

        <button class="ms-2 btn btn-light mb-4 d-sm-none d-inline btn-close-left-side">
            <i class="app-text-primary bi bi-x-lg"></i>
        </button>

        <div class="d-flex gap-2 align-items-center mb-5">
            <img src="{{ asset('storage/web/favicon.ico') }}" alt="{{ env('APP_NAME') }}">
            <h5 class="dashboard-title fw-bold">{{ env('APP_NAME') }}</h5>
        </div>

        <ul class="sidebar-nav-grid" id="sidebar-nav-grid">
            <h6 class="fw-bold">Menu</h6>
            <li class="nav-item-grid row-auto">
                <a class="nav-link-grid {{ Route::currentRouteName() == 'dashboard' ? 'active' : '  ' }}" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-table-columns"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid {{ Str::contains(Route::currentRouteName(), 'pendaftaran') ? 'active' : '' }}" href="{{ route('pendaftaran.index') }}">
                    <i class="fa-solid fa-clipboard"></i>
                    <span>Pendaftaran</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid {{ Route::currentRouteName() == 'back-office.index' ? 'active' : '' }}" href="{{ route('back-office.index') }}">
                    <i class="fa-solid fa-computer"></i>
                    <span>Back Office</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid {{ Route::currentRouteName() == 'penyerahan.index' ? 'active' : '' }}" href="{{ route('penyerahan.index') }}">
                    <i class="fa-solid fa-file-circle-check"></i>
                    <span>Penyerahan Izin</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid {{ Route::currentRouteName() == 'pemantauan.index' ? 'active' : '' }}" href="{{ route('pemantauan.index') }}">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span>Pemantauan</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid {{ Route::currentRouteName() == 'reporting.index' ? 'active' : '' }}" href="{{ route('reporting.index') }}">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Reporting</span>
                </a>
            </li>
            <h6 class="nav-item-grid row-auto fw-bold">Others</h6>
            <li class="nav-item-grid row-auto">
                <a class="nav-link-grid {{ '' }}" href="#">
                    <i class="fa-solid fa-network-wired"></i>
                    <span>API</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid {{ '' }}" href="#">
                    <i class="fa-solid fa-gear"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid {{ '' }}" href="#">
                    <i class="fa-solid fa-user"></i>
                    <span>Profil</span>
                </a>
            </li>
            <li class="nav-item-grid">
                <a class="nav-link-grid" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-power-off"></i>&nbsp;&nbsp;{{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>

    </aside>
</div>