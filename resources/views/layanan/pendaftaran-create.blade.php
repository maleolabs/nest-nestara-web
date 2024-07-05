@extends('layanan.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.pendaftaran.index') }}">
            Pendaftaran
        </a>
    </li>
    <li class="breadcrumb-item active">
        Tambah {{ $type }}
    </li>
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
    @livewire('dashboard.pendaftaran.pendaftaran-create')
@endsection