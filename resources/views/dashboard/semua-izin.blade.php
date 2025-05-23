@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('izin.index') }}">Semua Izin</a>
    </li>
@endpush
@section('main')
    @livewire('dashboard.component.all-izin')
@endsection