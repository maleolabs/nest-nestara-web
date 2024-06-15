@extends('dashboard.layouts.dashboard-app')
@push('breadcrumb-item')
    <li class="breadcrumb-item active">
        Pendaftaran
    </li>
    <li class="breadcrumb-item active" id="pendaftaran-sub-breadcrumb"></li>
@endpush
@section('main')
    @livewire('dashboard.pendaftaran.pendaftaran-index')
@endsection
@push('scripts')
    <script>
        let pendaftaranSubBread = document.getElementById("pendaftaran-sub-breadcrumb")
        Livewire.on('menu-selected', function (event) {
            pendaftaranSubBread.textContent = event[0].menu
        })
    </script>
@endpush