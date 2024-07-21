@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('izin.index') }}">Semua Izin</a>
    </li>
    <li class="breadcrumb-item">
        @php($current_izin = str_capitalize($izin->pemohon->nama_pemohon, true) . ' (' . Str::limit(str_capitalize(($izin->jenis_perizinan->nama), true), 70) . ')')
        {{ $current_izin }}
    </li>
@endpush
@section('main')
    <div class="card">
        <div class="card-header border-bottom">
            <h6 class="card-title mb-0">Detail Permohonan Izin</h6>
        </div>
        <div class="card-body">
            <ul class="list-group">
                @foreach ($details as $key => $detail)
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-md-5 text-capitalize">
                                {{ $key }}
                            </div>
                            <div class="col-md-7">
                                <p class="mb-1 text-sm text-muted">{{ $detail['tanggal'] }}</p>
                                @isset($detail['pegawai'])
                                    <p class="mb-1 text-sm text-dark text-bold">
                                        <i class="fa-solid fa-user-tie me-1"></i>
                                        Pegawai yang memeriksa: {{ $detail['pegawai'] }}
                                    </p>
                                @endisset
                                <p class="mb-1 text-sm text-capitalize{{ $detail['progres'] ? ' text-success' : ' text-warning' }}">{{ $detail['keterangan'] ?? '' }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection