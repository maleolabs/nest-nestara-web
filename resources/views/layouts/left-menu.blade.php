<div class="sidebar-container d-none" id="sideMenuLeftContainer">
    <aside class="side-menu-left p-3" id="sideMenuLeft">
        <div class="d-flex align-items-top justify-content-between mb-4">
            <img src="{{ asset('storage/web/favicon.ico') }}" width="35" height="35" alt="none" class="img-circle img-circle-sm">
            <button class="btn btn-transparent rounded-3 menu-left-btn-close m-0 px-3 py-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <ul class="sidebar-nav">
            @role('pengelola_layanan')
                <li class="nav-item ps-2 mb-3">
                    <img src="{{ asset('storage/web/layanan-icon.png') }}" width="35" height="35" class="img-fluid me-2">
                    <span class="text-sm">Layanan</span>
                </li>
                <div class="nav-devider">Pengelolaan izin</div>
                <li class="nav-item">
                    <a href="{{ route('layanan.pendaftaran') }}" class="nav-link{{ Route::currentRouteName() == 'layanan.pendaftaran' ? ' active' : '' }}">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Pendaftaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('layanan.penyerahan') }}" class="nav-link{{ Route::currentRouteName() == 'layanan.penyerahan' ? ' active' : '' }}">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Penyerahan</span>
                    </a>
                </li>
            @elserole('pegawai')
                <li class="nav-item ps-2 mb-3">
                    <img src="{{ asset('storage/web/employees.png') }}" width="35" height="35" class="img-fluid me-2">
                    <span class="text-sm">Back Office</span>
                </li>
                <div class="nav-devider">Pengelolaan izin</div>
                <li class="nav-item">
                    <a href="{{ route('pegawai.pendataan') }}" class="nav-link{{ Route::currentRouteName() == 'pegawai.pendataan' ? ' active' : '' }}">
                        <i class="fa-solid fa-file-signature"></i>
                        <span>Pendataan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pegawai.tim-teknis') }}" class="nav-link{{ Route::currentRouteName() == 'pegawai.tim-teknis' ? ' active' : '' }}">
                        <i class="fa-solid fa-people-group"></i>
                        <span>Tim Teknis</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pegawai.penetapan') }}" class="nav-link{{ Route::currentRouteName() == 'pegawai.penetapan' ? ' active' : '' }}">
                        <i class="fa-solid fa-file-circle-check"></i>
                        <span>Penetapan</span>
                    </a>
                </li>
            @endrole
        </ul>
    </aside>
</div>