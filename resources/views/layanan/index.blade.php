@extends('layanan.layouts.app')
@section('main')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card bg-gradient-primary">
                    <div class="card-body">
                        {{ $statistikJenisPerizinan->container() }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h4 class="mb-0 text-bold">Permohonan izin</h4>
                    </div>
                    <div class="card-body">
                        @forelse ($all_izin as $izin)
                            <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($izin->id)]) }}" class="d-block mb-2">
                                <span class="text-muted text-xs">{{ $izin->created_at->diffForHumans() }}</span>
                                <p class="text-md text-bold mb-0">{{ str_capitalize($izin->pemohon->nama_pemohon, true) . ' (' . str_capitalize($izin->jenis_perizinan->nama, true) . ')' }}</p>
                                @php
                                    $status = $izin->status_izin->last();
                                    $statusClasses = getStatusIzinClasses($status);
                                @endphp
                                <span class="ps-0 badge badge-dot">
                                    <i class="{{ $statusClasses['bgClass'] }}"></i>
                                    <span class="text-sm {{ $statusClasses['textClass'] }}">
                                        {{ $status->nama }}
                                    </span>
                                </span>
                            </a>
                        @empty
                            <p class="text-center text-muted">Belum ada permohonan izin</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>        
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/front-office.js') }}"></script>
    <script src="{{ $statistikJenisPerizinan->cdn() }}"></script>
    {{ $statistikJenisPerizinan->script() }}
@endpush