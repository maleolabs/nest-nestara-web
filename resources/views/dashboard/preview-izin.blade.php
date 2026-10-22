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
    <div class="rounded-2xl border border-ink-200 bg-white">
        <div class="border-b border-ink-200 px-5 py-4">
            <h1 class="font-display text-xl font-extrabold text-ink-900">Detail Permohonan Izin</h1>
        </div>
        <div class="p-5">
            <ul class="divide-y divide-ink-200 overflow-hidden rounded-xl border border-ink-200">
                @foreach ($details as $key => $detail)
                    <li class="p-3">
                        <h2 class="text-sm font-extrabold uppercase tracking-wide text-ink-900">
                            {{ $key }}
                        </h6>
                        @isset($detail['tanggal'])
                        <p class="mb-1 text-sm text-ink-500">
                                <i class="fa-regular fa-calendar-check me-1"></i>
                                {{ $detail['tanggal'] }}
                            </p>
                        @endisset
                        @isset($detail['pegawai'])
                                <p class="mb-1 text-sm font-bold text-ink-800">
                                <i class="fa-solid fa-user-tie me-1"></i>
                                Pegawai yang memeriksa: {{ $detail['pegawai'] }}
                            </p>
                        @endisset
                        <p class="mb-1 text-sm capitalize{{ $detail['progres'] ? ' text-emerald-600' : ' text-red-600' }}">
                            <i class="fa-solid {{ $detail['progres'] ? 'fa-circle-check' : 'fa-xmark-circle' }} me-1"></i>
                            {{ $detail['keterangan'] ?? '' }}
                        </p>
                        @isset($detail['data'])
                            @foreach ($detail['data'] as $key_data => $detail_data)
                                <p class="mt-3 mb-1 text-sm text-dark text-bold text-capitalize">
                                    {{ $key_data }}
                                </p>
                                @if (is_array($detail_data))
                                    <ul class="mb-3 divide-y divide-ink-200 overflow-hidden rounded-lg border border-ink-200">
                                        @foreach ($detail_data['value'] as $field => $data)
                                            <li class="py-0">
                                                <div class="grid grid-cols-1 sm:grid-cols-2">
                                                    @if ($detail_data['tipe'] == 'file')
                                                        <div class="py-2 sm:px-3">
                                                            <p class="mb-0">{{ str_capitalize($data['syarat_izin'], true) }}</p>
                                                        </div>
                                                        <div class="border-t border-ink-200 py-2 sm:border-l sm:border-t-0 sm:px-3">
                                                            <a title="download" href="{{ \App\Support\SignedFileUrl::make($data['nama_file']) }}" class="text-decoration-underline" target="blank">
                                                                <i class="fa-solid fa-download me-1"></i>
                                                                {{ $data['nama_file'] }}
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="py-2 sm:px-3">
                                                            <p class="text-sm mb-0 text-muted text-capitalize">{{ str_replace('_', ' ', $field) }}</p>
                                                        </div>
                                                        <div class="border-t border-ink-200 py-2 sm:border-l sm:border-t-0 sm:px-3">
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
