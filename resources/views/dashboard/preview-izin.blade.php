@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('izin.index') }}">Semua Izin</a>
    </li>
    <li class="breadcrumb-item">
        @php($current_izin = str_capitalize($izin->pemohon->nama_pemohon, true) . ' (' . str_capitalize(($izin->jenis_perizinan->nama), true) . ')')
        {{ $current_izin }}
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