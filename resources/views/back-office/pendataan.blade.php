@extends('back-office.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('pegawai.pendataan') }}">
            Pendataan
        </a>
    </li>
    <li class="breadcrumb-item active" id="pendataan-sub-breadcrumb"></li>
@endpush
@section('main')
    @livewire('dashboard.back-office.pendataan')
@endsection
@push('scripts')
    <script>
        let pendaftaranSubBread = document.getElementById("pendataan-sub-breadcrumb")
        Livewire.on('menu-selected', function (event) {
            pendaftaranSubBread.textContent = event[0].menu
        })
    </script>
@endpush