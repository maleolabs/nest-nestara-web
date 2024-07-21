@extends('layanan.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('layanan.pendaftaran') }}">
            Pendaftaran
        </a>
    </li>
    <li class="breadcrumb-item active" id="pendaftaran-sub-breadcrumb"></li>
@endpush
@push('nav-dashboard-content')
    <div class="dropdown">
        <a href="javasciprt:;" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 dropdown-toggle p-2" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
            <i class="fa-solid fa-square-plus fs-5"></i>
            Tambah
        </a>
        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
            <li>
                <a class="dropdown-item" href="{{ route('layanan.pendaftaran.create', ['type' => 'data-pemohon']) }}">
                    <i class="fa-solid fa-users me-2"></i>
                    Data Pemohon
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('layanan.pendaftaran.create', ['type' => 'izin-baru']) }}">
                    <i class="fa-solid fa-file-circle-plus me-2"></i>
                    Izin Baru
                </a>
            </li>
        </ul>
    </div>
@endpush
@section('main')
    @livewire('dashboard.pendaftaran.pendaftaran-index')
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/front-office.js') }}"></script>
    <script>
        let pendaftaranSubBread = document.getElementById("pendaftaran-sub-breadcrumb")
        Livewire.on('menu-selected', function (event) {
            pendaftaranSubBread.textContent = event[0].menu
        })
        let layoutDropdownIcon = document.getElementById("layoutDropdownIcon")
        function setDropdownValue(value) {
            if (value == 'list') {
                layoutDropdownIcon.classList.remove('bi', 'bi-grid-fill', 'text-sm')
                layoutDropdownIcon.classList.add('fa-solid', 'fa-list', 'fs-6')
            } else if (value == 'grid') {
                layoutDropdownIcon.classList.remove('fa-solid', 'fa-list', 'fs-6')
                layoutDropdownIcon.classList.add('bi', 'bi-grid-fill', 'text-sm')
            }
            Livewire.dispatch('change-layout', {value: value})
            let url = new URL(window.location)
            url.searchParams.set('layout', value)
            window.history.pushState({}, '', url)
        }
    </script>
@endpush