@extends('back-office.layouts.app')
@section('main')
<div class="row border-top min-vh-100">
    <div class="col-3 border-end p-4">
        <h6 class="mb-2 text-bold">Pemohon</h6>
        @foreach ($all_pemohon as $pemohon)
            <div class="d-flex mb-2 align-items-center gap-2">
                <img src="{{ asset('storage/users/' . ($pemohon->user ? $pemohon->user->foto : 'profile.png')) }}" alt="{{ $pemohon->nama_pemohon }}" class="img-circle img-circle-xs">
                <div>
                    <h6 class="text-xs mb-0">
                        <span class="fw-semibold">{{ $pemohon->nama_pemohon }}</span>
                        @if ($pemohon->user)
                            <span class="ms-2 text-muted">
                                <i class="fa-solid fa-at"></i>
                                {{ $pemohon->user->username }}
                            </span>
                        @endif
                    </h6>
                    <p class="text-muted text-xs mb-0">{{ $pemohon->user ? $pemohon->user->getRoleNames()[0] : 'pemohon' }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-9 bg-white p-4">
        <div class="row">
            <div class="col-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-bold mb-0">Dashboard</h5>
                    <div>
                        <p class="text-sm fw-semibold text-end mb-0">Izin</p>
                        <a href="{{ route('izin.index') }}" class="text-xs text-end d-block">
                            lihat semua
                            <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                        </a>
                    </div>
                </div>
                @forelse ($all_izin as $izin)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <img src="{{ asset('storage/users/' . $izin->pemohon->user->foto) }}" alt="{{ $izin->pemohon->nama }}" class="img-circle img-circle-sm">
                                <div>
                                    <h6 class="text-sm fw-semibold mb-0">{{ $izin->pemohon->nama_pemohon }}</h6>
                                    <span class="text-muted text-xs">{{ $izin->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="rounded bg-light px-3 py-2">
                            <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($izin->id)]) }}" class="d-block">
                                <p class="text-md text-bold mb-0">{{ str_capitalize($izin->jenis_perizinan->nama, true) }}</p>
                                @php
                                    $status = $izin->status_izin->last();
                                    $statusClasses = getStatusIzinClasses($status);
                                @endphp
                            </a>
                            <span class="ps-0 badge badge-dot">
                                <i class="{{ $statusClasses['bgClass'] }}"></i>
                                <span class="text-sm {{ $statusClasses['textClass'] }}">
                                    {{ $status->nama }}
                                </span>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="border rounded-3 p-3">
                        <p class="text-center text-muted">Belum ada permohonan izin</p>
                    </div>
                @endforelse
            </div>
            <div class="col-4">
                <div class="card bg-gradient-primary mb-0 p-2">
                    {{ $statistikJenisPerizinan->container() }}
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