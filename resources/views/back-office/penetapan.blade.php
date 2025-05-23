@extends('back-office.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('pegawai.penetapan') }}">
            Penetapan
        </a>
    </li>
    <li class="breadcrumb-item active" id="penetapan-sub-breadcrumb"></li>
@endpush
@section('main')
    @livewire('dashboard.back-office.penetapan')
@endsection
@push('scripts')
    <script>
        let penetapanSubBread = document.getElementById("penetapan-sub-breadcrumb")
        Livewire.on('menu-selected', function (event) {
            penetapanSubBread.textContent = event[0].menu
        })
    </script>
@endpush