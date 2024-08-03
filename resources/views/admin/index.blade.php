@extends('dashboard.app')
@section('main')
    <div class="row border-top min-vh-100">
        <div class="col-3 py-5 ps-5 pe-4 border-end">
            <h6 class="text-sm fw-semibold">Daftar pengguna</h6>
            @foreach ($users as $user)
                @if ($user->id != auth()->user()->id)
                    <div class="d-flex mb-2 align-items-center gap-2">
                        <img src="{{ asset('storage/users/' . $user->foto) }}" alt="{{ $user->username }}" class="img-circle img-circle-xs">
                        <div>
                            <h6 class="text-xs mb-0">
                                @if ($user->hasRole(PEMOHON_ROLE))
                                    <span class="fw-semibold">{{ $user->pemohon->nama_pemohon }}</span>
                                @elseif($user->hasAnyRole([FRONT_OFFICE_ROLE, BACK_OFFICE_ROLE]))
                                    <span class="fw-semibold">{{ $user->pegawai->nama }}</span>
                                @else
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                @endif
                                <span class="text-muted">
                                    <i class="fa-solid fa-at"></i>
                                    {{ $user->username }}
                                </span>
                            </h6>
                            <p class="text-muted text-xs mb-0">{{ $user->getRoleNames()[0] }}</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="col-9 py-4 ps-4 pe-5 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-bold mb-0">Dashboard</h5>
                <div>
                    <p class="text-sm fw-semibold mb-0">Jenis Perizinan</p>
                    <a href="{{ route('admin.jenis-perizinan.index') }}" class="text-xs text-end d-block">
                        lihat semua
                        <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                    </a>
                </div>
            </div>
            @foreach ($jenis_perizinan as $jenis)
                <div class="border rounded mb-3 p-3">
                    <h6 class="fw-semibold">{{ str_capitalize(Str::limit($jenis->nama, 100), true) }}</h6>
                    <div class="rounded bg-light px-3 py-2">
                        <p class="text-sm mb-0 d-flex align-items-center gap-1">
                            <span class="fs-4">&bull;</span>
                            {{ $jenis->kelompok_izin }}
                        </p>
                        <p class="text-sm mb-0 d-flex align-items-center gap-1">
                            <span class="fs-4">&bull;</span>
                            {{ $jenis->durasi }}
                        </p>
                        <p class="text-sm mb-0 d-flex align-items-center gap-1">
                            <span class="fs-4 m-0 p-0">&bull;</span>
                            {{ $jenis->masa_berlaku }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection