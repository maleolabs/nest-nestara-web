@extends('admin.layouts.dashboard-app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.back-office.index') }}">
            Back Office
        </a>
    </li>
    <li class="breadcrumb-item active" id="office-sub-breadcrumb"></li>
@endpush
@section('main')
    @livewire('admin.back-office.office-index')
@endsection
@push('scripts')
    <script>
        let pendaftaranSubBread = document.getElementById("office-sub-breadcrumb")
        Livewire.on('menu-selected', function (event) {
            pendaftaranSubBread.textContent = event[0].menu
        })
    </script>
@endpush