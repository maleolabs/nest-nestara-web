<div class="sidebar-container d-none" id="sideMenuLeftContainer">
    <aside class="side-menu-left p-3" id="sideMenuLeft">
        <div class="d-flex align-items-top justify-content-between mb-4">
            <img src="{{ asset('storage/web/favicon.ico') }}" width="35" height="35" alt="none" class="img-circle img-circle-sm">
            <button class="btn btn-transparent rounded-3 menu-left-btn-close m-0 px-3 py-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <ul class="sidebar-nav">
            @role(FRONT_OFFICE_ROLE)
                <li class="nav-item ps-2 mb-3">
                    <img src="{{ asset('storage/web/layanan-icon.png') }}" width="35" height="35" class="img-fluid me-2">
                    <span class="text-sm">Layanan</span>
                </li>
                <div class="nav-divider mt-3 mb-2">Pengelolaan izin</div>
                <li class="nav-item">
                    <a href="{{ route('layanan.pendaftaran') }}" class="nav-link{{ Route::currentRouteName() == 'layanan.pendaftaran' ? ' active' : '' }}">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Pendaftaran</span>
                        <span id="pendaftaranCount" class="ms-auto badge rounded-pill bg-danger text-white">{{ countByProcess('pendaftaran') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('layanan.penyerahan') }}" class="nav-link{{ Route::currentRouteName() == 'layanan.penyerahan' ? ' active' : '' }}">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Penyerahan</span>
                        <span id="penyerahanCount" class="ms-auto badge rounded-pill bg-danger text-white">{{ countByProcess('penyerahan') }}</span>
                    </a>
                </li>
            @elserole(BACK_OFFICE_ROLE)
                <li class="nav-item ps-2 mb-3">
                    <img src="{{ asset('storage/web/employees.png') }}" width="35" height="35" class="img-fluid me-2">
                    <span class="text-sm">Back Office</span>
                </li>
                <div class="nav-divider mt-3 mb-2">Pengelolaan izin</div>
                <li class="nav-item">
                    <a href="{{ route('pegawai.pendataan') }}" class="nav-link{{ Route::currentRouteName() == 'pegawai.pendataan' ? ' active' : '' }}">
                        <i class="fa-solid fa-file-signature"></i>
                        <span>Pendataan</span>
                        <span id="pendataanCount" class="ms-auto badge rounded-pill bg-danger text-white">{{ countByProcess('pendataan') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pegawai.rekomendasi') }}" class="nav-link{{ Route::currentRouteName() == 'pegawai.rekomendasi' ? ' active' : '' }}">
                        <i class="fa-solid fa-medal"></i>
                        <span>Rekomendasi</span>
                        <span id="rekomendasiCount" class="ms-auto badge rounded-pill bg-danger text-white">{{ countByProcess('rekomendasi') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pegawai.penetapan') }}" class="nav-link{{ Route::currentRouteName() == 'pegawai.penetapan' ? ' active' : '' }}">
                        <i class="fa-solid fa-file-circle-check"></i>
                        <span>Penetapan</span>
                        <span id="penetapanCount" class="ms-auto badge rounded-pill bg-danger text-white">{{ countByProcess('penetapan') }}</span>
                    </a>
                </li>
            @endrole
            @livewire('dashboard.component.left-menu-content')
        </ul>
    </aside>
</div>
@push('scripts')
    <script src="{{ asset('assets/js/notif-count.js') }}"></script>
@endpush