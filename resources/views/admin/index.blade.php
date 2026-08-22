@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="text-xs font-semibold text-ink-600">/ Dashboard</li>
@endpush
@section('main')
<div class="space-y-6">
    {{-- header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Admin Console</p>
            <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Selamat datang, {{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-ink-600">Ringkasan sistem perizinan — katalog aktif dan aktivitas pengguna.</p>
        </div>
        <a href="{{ route('admin.jenis-perizinan.index') }}" class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-bold text-white shadow-[0_8px_20px_rgba(15,118,110,0.25)] hover:bg-nest-800 transition">
            Kelola Perizinan <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    {{-- stats --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
            <div class="flex items-center justify-between">
                <div class="h-10 w-10 rounded-xl bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200"><i class="fa-solid fa-file-invoice"></i></div>
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200">Aktif</span>
            </div>
            <p class="mt-3 text-[28px] font-extrabold leading-none text-ink-900">{{ count($jenis_perizinan) }}</p>
            <p class="mt-1 text-xs font-bold tracking-wide text-ink-500 uppercase">Jenis Perizinan terdaftar</p>
        </div>
        <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
            <div class="flex items-center justify-between">
                <div class="h-10 w-10 rounded-xl bg-sky-50 text-sky-700 grid place-items-center ring-1 ring-sky-200"><i class="fa-solid fa-users"></i></div>
                <span class="rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-bold text-sky-700 ring-1 ring-sky-200">Terdaftar</span>
            </div>
            <p class="mt-3 text-[28px] font-extrabold leading-none text-ink-900">{{ count($users) }}</p>
            <p class="mt-1 text-xs font-bold tracking-wide text-ink-500 uppercase">Total pengguna sistem</p>
        </div>
        <div class="rounded-2xl bg-gradient-to-br from-nest-700 to-nest-800 p-5 text-white shadow-[0_12px_32px_rgba(15,118,110,0.30)]">
            <div class="flex items-center justify-between">
                <div class="h-10 w-10 rounded-xl bg-white/15 grid place-items-center"><i class="fa-solid fa-clock"></i></div>
                <span class="rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-bold">WITA</span>
            </div>
            <p class="mt-3 text-[22px] font-extrabold leading-tight">08.00 – 16.00</p>
            <p class="mt-1 text-xs font-semibold text-white/75 uppercase tracking-wide">Jam operasional layanan • Senin–Jumat</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[340px_1fr]">
        {{-- users list --}}
        <aside class="rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden h-fit">
            <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-extrabold text-ink-900">Pengguna Terdaftar</h3>
                    <p class="text-xs text-ink-500">{{ count($users) - 1 }} akun selain Anda</p>
                </div>
                <span class="h-9 w-9 rounded-xl bg-ink-50 grid place-items-center text-ink-500 ring-1 ring-ink-200"><i class="fa-solid fa-users-line text-sm"></i></span>
            </div>
            <ul class="max-h-[520px] divide-y divide-ink-100 overflow-y-auto p-2">
                @foreach ($users as $user)
                    @if ($user->id != auth()->user()->id)
                        <li class="flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-ink-50 transition">
                            <img src="{{ asset('storage/users/' . $user->foto) }}" alt="{{ $user->username }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-ink-200">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-ink-900 leading-tight">
                                    @if ($user->hasRole(PEMOHON_ROLE))
                                        {{ $user->pemohon ? $user->pemohon->nama_pemohon : $user->name }}
                                    @elseif($user->hasAnyRole([FRONT_OFFICE_ROLE, BACK_OFFICE_ROLE]))
                                        {{ $user->pegawai->nama }}
                                    @else
                                        {{ $user->name }}
                                    @endif
                                </p>
                                <p class="truncate text-xs text-ink-500"><i class="fa-solid fa-at mr-0.5"></i>{{ $user->username }} • {{ $user->getRoleNames()[0] }}</p>
                            </div>
                            @if ($user->hasRole(PEMOHON_ROLE))
                                <span class="shrink-0 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 ring-1 ring-amber-200">Pemohon</span>
                            @elseif($user->hasRole(FRONT_OFFICE_ROLE))
                                <span class="shrink-0 rounded-full bg-sky-50 px-2 py-0.5 text-[10px] font-bold text-sky-700 ring-1 ring-sky-200">FO</span>
                            @elseif($user->hasRole(BACK_OFFICE_ROLE))
                                <span class="shrink-0 rounded-full bg-violet-50 px-2 py-0.5 text-[10px] font-bold text-violet-700 ring-1 ring-violet-200">BO</span>
                            @else
                                <span class="shrink-0 rounded-full bg-ink-900 px-2 py-0.5 text-[10px] font-bold text-white">Admin</span>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </aside>

        {{-- jenis perizinan list --}}
        <section class="rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-extrabold text-ink-900">Katalog Jenis Perizinan</h3>
                    <p class="text-xs text-ink-500">Ringkasan kelompok izin, durasi proses, dan masa berlaku.</p>
                </div>
                <a href="{{ route('admin.jenis-perizinan.index') }}" class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-xs font-bold text-ink-700 hover:border-nest-300 hover:bg-nest-50 transition">
                    Lihat semua <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </a>
            </div>
            <div class="divide-y divide-ink-100 p-2">
                @forelse ($jenis_perizinan as $jenis)
                    <div class="group rounded-xl px-3 py-3 hover:bg-ink-50 transition">
                        <div class="flex items-start gap-3">
                            <div class="h-9 w-9 shrink-0 rounded-lg bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200 group-hover:bg-nest-700 group-hover:text-white transition">
                                <i class="fa-solid fa-file-shield text-xs"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-ink-900 leading-snug">{{ str_capitalize(Str::limit($jenis->nama, 90), true) }}</p>
                                <div class="mt-1.5 flex flex-wrap gap-1.5">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-ink-50 px-2 py-0.5 text-[11px] font-semibold text-ink-600 ring-1 ring-ink-200"><i class="fa-solid fa-layer-group text-[9px]"></i> {{ $jenis->kelompok_izin }}</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-ink-50 px-2 py-0.5 text-[11px] font-semibold text-ink-600 ring-1 ring-ink-200"><i class="fa-solid fa-hourglass-half text-[9px]"></i> Durasi {{ $jenis->durasi }}</span>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-ink-50 px-2 py-0.5 text-[11px] font-semibold text-ink-600 ring-1 ring-ink-200"><i class="fa-solid fa-calendar-check text-[9px]"></i> Berlaku {{ $jenis->masa_berlaku }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-inbox"></i></div>
                        <p class="mt-3 text-sm font-bold text-ink-900">Belum ada jenis perizinan</p>
                        <p class="text-sm text-ink-500">Tambahkan dari menu Jenis Perizinan.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
