<div class="sidebar-container d-none" id="sidebarContainer">
    <aside class="sidebar-right p-3" id="sidebarRight">
        <div class="d-flex align-items-top justify-content-between mb-4">
            <img src="{{ asset('storage/users/' . auth()->user()->foto) }}" alt="none" class="img-circle img-circle-sm">
            <div class="ms-2 me-auto">
                <h6 class="text-sm spacing-normal mb-0">{{ Str::limit(auth()->user()->name, 17) }}</h6>
                <p class="text-muted m-0 text-sm">
                    {{ Str::limit(auth()->user()->email, 20) }}
                </p>
            </div>
            <button class="btn btn-transparent rounded-3 sidebar-btn-close px-3 py-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-item">
                @unlessrole(PEMOHON_ROLE)
                    <a href="{{ route('dashboard') }}" class="nav-link{{ str_contains(Route::currentRouteName(), 'dashboard') ? ' active' : '' }}">
                        <i class="fa-solid fa-table-columns"></i>
                        <span>Dashboard</span>
                    </a>
                @endrole
                <a href="{{ route('profile') }}" class="nav-link{{ str_contains(Route::currentRouteName(), 'profile') ? ' active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                    <span>Profil</span>
                </a>
            </li>
            <hr>
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>
</div>