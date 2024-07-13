@extends('back-office.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('pegawai.tim-teknis') }}">
            Tim Teknis
        </a>
    </li>
    <li class="breadcrumb-item active" id="tim-teknis-sub-breadcrumb"></li>
@endpush
@section('main')
    @livewire('dashboard.back-office.tim-teknis')
@endsection
@push('scripts')
    <script>
        let pendaftaranSubBread = document.getElementById("tim-teknis-sub-breadcrumb")
        Livewire.on('menu-selected', function (event) {
            pendaftaranSubBread.textContent = event[0].menu
        })
    </script>
@endpush