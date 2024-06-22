<div class="sidebar-container d-none" id="sidebarContainer">
    <aside class="sidebar-right p-3" id="sidebarRight">
        <div class="d-flex align-items-center justify-content-between">
            <img src="{{ asset('storage/web/favicon.ico') }}" alt="none" class="img-circle img-circle-sm">
            <div class="ms-2 me-auto">
                <h6 class="fw-bold m-0">
                    {{ Str::limit(auth()->user()->email, 20) }}
                </h6>
            </div>
            <button class="btn btn-light rounded-3 sidebar-btn-close mb-0 ms-2 ps-3">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <hr>
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link">
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