@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('izin.index') }}">Semua Izin</a>
    </li>
@endpush
@section('main')
    <div class="card">
        <div class="card-header border-bottom">
            <h6 class="card-title mb-0">Izin</h6>
        </div>
        <div class="card-body">

        </div>
    </div>
@endsection