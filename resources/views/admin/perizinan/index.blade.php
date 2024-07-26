@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">Jenis Perizinan</a>
    </li>
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