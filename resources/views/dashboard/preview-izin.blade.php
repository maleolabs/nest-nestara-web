@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('izin.index') }}">Semua Izin</a>
    </li>
    <li class="breadcrumb-item">
        @php($current_izin = str_capitalize($izin->pemohon->nama_pemohon, true) . ' (' . str_capitalize(($izin->jenis_perizinan->nama), true) . ')')
        {{ Str::limit($current_izin, 70) }}
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
                    <li class="list-group-item p-3">
                        <h6 class="text-uppercase">
                            {{ $key }}
                        </h6>
                        @isset($detail['tanggal'])
                            <p class="mb-1 text-sm text-muted">
                                <i class="fa-regular fa-calendar-check me-1"></i>
                                {{ $detail['tanggal'] }}
                            </p>
                        @endisset
                        @isset($detail['pegawai'])
                            <p class="mb-1 text-sm text-dark text-bold">
                                <i class="fa-solid fa-user-tie me-1"></i>
                                Pegawai yang memeriksa: {{ $detail['pegawai'] }}
                            </p>
                        @endisset
                        <p class="mb-1 text-sm text-capitalize{{ $detail['progres'] ? ' text-success' : ' text-danger' }}">
                            <i class="fa-solid {{ $detail['progres'] ? 'fa-circle-check' : 'fa-xmark-circle' }} me-1"></i>
                            {{ $detail['keterangan'] ?? '' }}
                        </p>
                        @isset($detail['data'])
                            @foreach ($detail['data'] as $key_data => $detail_data)
                                <p class="mt-3 mb-1 text-sm text-dark text-bold text-capitalize">
                                    {{ $key_data }}
                                </p>
                                @if (is_array($detail_data))
                                    <ul class="list-group mb-3">
                                        @foreach ($detail_data['value'] as $field => $data)
                                            <li class="list-group-item py-0">
                                                <div class="row">
                                                    @if ($detail_data['tipe'] == 'file')
                                                        <div class="col-6 py-2">
                                                            <p class="mb-0">{{ str_capitalize($data['syarat_izin'], true) }}</p>
                                                        </div>
                                                        <div class="col-6 py-2 border-start">
                                                            <a title="download" href="{{ \App\Support\SignedFileUrl::make($data['nama_file']) }}" class="text-decoration-underline" target="blank">
                                                                <i class="fa-solid fa-download me-1"></i>
                                                                {{ $data['nama_file'] }}
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="col-3 py-2">
                                                            <p class="text-sm mb-0 text-muted text-capitalize">{{ str_replace('_', ' ', $field) }}</p>
                                                        </div>
                                                        <div class="col-9 border-start py-2">
                                                            <h6 class="text-sm mb-0 text-dark text-uppercase">{{ $data }}</h6>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        @endisset
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
