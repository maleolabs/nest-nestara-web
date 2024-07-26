@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">Jenis Perizinan</a>
    </li>
@endpush
@push('nav-dashboard-content')
    <div class="d-flex gap-2 align-items-center w-75 justify-content-end">
        <div class="dropdown">
            <button class="btn btn-sm btn-light text-capitalize fw-medium text-nowrap" type="button" data-bs-toggle="dropdown" id="filterBtn">
                semua
                <i class="fa-solid fa-filter ms-2 text-xs"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                <li>
                    <a class="dropdown-item" href="javascript:;" id="filterAll">
                      Semua
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="javascript:;" id="filterAvailable">
                      Tersedia
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="javascript:;" id="filterNotAvailable">
                      Tidak Tersedia
                    </a>
                </li>
            </ul>
        </div>
        <div class="input-group input-group-sm rounded-3 border border-light w-75">
            <input type="search" class="form-control border-0" placeholder="cari jenis perizinan">
        </div>
    </div>
@endpush
@section('main')
    <div id="data-perizinan-container" data-load-url="{{ route('load-data-perizinan') }}">
        <div class="row"></div>
    </div>
    <div id="loading-message">
        <div class="row"></div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/paginate.js') }}"></script>
@endpush