@extends('layanan.layouts.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('layanan.penyerahan') }}">
            Penyerahan
        </a>
    </li>
@endpush
@section('main')
    <div class="container-fluid mt-4">
        @livewire('dashboard.penyerahan.penyerahan-index')
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/front-office.js') }}"></script>
@endpush