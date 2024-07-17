@extends('back-office.layouts.app')
@section('main')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card bg-gradient-primary">
                    <div class="card-header bg-transparent">
                        <h4 class="text-bold app-text-primary">
                            Back Office
                        </h4>
                    </div>
                    <div class="card-body">
                        {{ $statistikJenisPerizinan->container() }}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="text-bold mb-0 text-primary">Permohonan izin</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($all_izin as $izin)
                            <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($izin->id)]) }}" class="mb-2 d-block">
                                <span class="text-muted text-xs">
                                    {{ $izin->created_at->diffForHumans() }}
                                </span>
                                <p class="text-sm text-bold mb-0">
                                    {{ str_capitalize($izin->pemohon->nama_pemohon, true) . ' (' . str_capitalize($izin->jenis_perizinan->nama, true) . ')' }}
                                </p>
                                @php
                                    $status = $izin->status_izin->last();
                                    $statusClasses = getStatusIzinProcessClasses($status);
                                @endphp
                                <span class="ps-0 badge badge-dot">
                                    <i class="{{ $statusClasses['bgClass'] }}"></i>
                                    <span class="text-sm {{ $statusClasses['textClass'] }}">
                                        {{ $status->keterangan }}
                                    </span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ $statistikJenisPerizinan->cdn() }}"></script>
    {{ $statistikJenisPerizinan->script() }}    
@endpush