@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}">Jenis Perizinan</a>
    </li>
@endpush
@section('main')
    @livewire('admin.jenis-perizinan.perizinan-index')
@endsection