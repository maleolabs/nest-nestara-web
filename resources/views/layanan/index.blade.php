@extends('layanan.layouts.app')
@push('breadcrumb-item')
    <li class="text-xs font-semibold text-ink-600">/ Dashboard Layanan</li>
@endpush
@section('main')
<div class="space-y-6">
    {{-- header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Front Office</p>
            <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Dashboard Layanan</h1>
            <p class="mt-1 text-sm text-ink-600">Pantau permohonan masuk, aktivitas pemohon, dan statistik perizinan.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('layanan.pendaftaran') }}" class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-bold text-white shadow-[0_8px_20px_rgba(15,118,110,0.25)] hover:bg-nest-800 transition">
                <i class="fa-solid fa-book-open text-xs"></i> Buka Pendaftaran
            </a>
            <a href="{{ route('izin.index') }}" class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-5 py-2.5 text-sm font-bold text-ink-700 hover:border-nest-300 hover:bg-nest-50 transition">
                Semua Izin <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
            </a>
        </div>
    </div>

    {{-- stats --}}
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
            <div class="flex items-center justify-between">
                <div class="h-10 w-10 rounded-xl bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200"><i class="fa-solid fa-inbox"></i></div>
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200">Berjalan</span>
            </div>
            <p class="mt-3 text-[28px] font-extrabold leading-none text-ink-900">{{ count($all_izin) }}</p>
            <p class="mt-1 text-xs font-bold tracking-wide text-ink-500 uppercase">Permohonan aktif (10 terbaru)</p>
        </div>
        <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
            <div class="flex items-center justify-between">
                <div class="h-10 w-10 rounded-xl bg-sky-50 text-sky-700 grid place-items-center ring-1 ring-sky-200"><i class="fa-solid fa-users"></i></div>
                <span class="rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-bold text-sky-700 ring-1 ring-sky-200">Terdaftar</span>
            </div>
            <p class="mt-3 text-[28px] font-extrabold leading-none text-ink-900">{{ count($all_pemohon) }}</p>
            <p class="mt-1 text-xs font-bold tracking-wide text-ink-500 uppercase">Pemohon terbaru</p>
        </div>
        <div class="rounded-2xl bg-gradient-to-br from-nest-700 to-nest-800 p-5 text-white shadow-[0_12px_32px_rgba(15,118,110,0.30)]">
            <div class="flex items-center justify-between">
                <div class="h-10 w-10 rounded-xl bg-white/15 grid place-items-center"><i class="fa-solid fa-bolt"></i></div>
                <span class="rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-bold">FO</span>
            </div>
            <p class="mt-3 text-[18px] font-extrabold leading-tight">Pendaftaran & Penyerahan</p>
            <p class="mt-1 text-xs font-semibold text-white/75">Kelola alur awal & akhir layanan perizinan.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[340px_1fr]">
        {{-- pemohon list --}}
        <aside class="h-fit rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden">
            <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
                <div>
                    <h3 class="text-sm font-extrabold text-ink-900">Pemohon Terbaru</h3>
                    <p class="text-xs text-ink-500">{{ count($all_pemohon) }} pemohon terdaftar</p>
                </div>
                <span class="h-9 w-9 rounded-xl bg-ink-50 grid place-items-center text-ink-500 ring-1 ring-ink-200"><i class="fa-solid fa-users-line text-sm"></i></span>
            </div>
            <ul class="max-h-[480px] divide-y divide-ink-100 overflow-y-auto p-2">
                @forelse ($all_pemohon as $pemohon)
                    <li class="flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-ink-50 transition">
                        <img src="{{ asset('storage/users/' . ($pemohon->user ? $pemohon->user->foto : 'profile.png')) }}" alt="{{ $pemohon->nama_pemohon }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-ink-200">
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-ink-900 leading-tight">{{ $pemohon->nama_pemohon }}</p>
                            <p class="truncate text-xs text-ink-500">
                                @if ($pemohon->user)<i class="fa-solid fa-at mr-0.5"></i>{{ $pemohon->user->username }} • @endif ID {{ $pemohon->nomor_id }}
                            </p>
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-sm text-ink-500">Belum ada pemohon</li>
                @endforelse
            </ul>
        </aside>

        {{-- izin feed + chart --}}
        <section class="space-y-6">
            <div class="rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink-100 px-5 py-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-ink-900">Permohonan Izin Terbaru</h3>
                        <p class="text-xs text-ink-500">Klik item untuk membuka detail & verifikasi.</p>
                    </div>
                    <a href="{{ route('izin.index') }}" class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-xs font-bold text-ink-700 hover:border-nest-300 hover:bg-nest-50 transition">
                        Lihat semua <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
                <ul class="divide-y divide-ink-100 p-2">
                    @forelse ($all_izin as $izin)
                        @php
                            $status = $izin->latestStatus;
                            $statusClasses = getStatusIzinClasses($status);
                        @endphp
                        <li>
                            <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($izin->id)]) }}" class="group flex items-start gap-3 rounded-xl px-3 py-3 hover:bg-ink-50 transition">
                                <img src="{{ asset('storage/users/' . $izin->pemohon->user->foto) }}" alt="{{ $izin->pemohon->nama }}" class="h-10 w-10 shrink-0 rounded-full object-cover ring-1 ring-ink-200">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-x-2">
                                        <p class="text-sm font-bold text-ink-900">{{ str_capitalize($izin->jenis_perizinan->nama, true) }}</p>
                                        <span class="text-[11px] font-semibold text-ink-400">{{ $izin->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-0.5 text-xs text-ink-500">{{ $izin->pemohon->nama_pemohon }} • No. Pendaftran {{ $izin->no_pendaftaran }}</p>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-bold ring-1 ring-ink-200 {{ $statusClasses['textClass'] }}">
                                    <i class="{{ $statusClasses['bgClass'] }} h-1.5 w-1.5 rounded-full"></i> {{ $status->nama }}
                                </span>
                            </a>
                        </li>
                    @empty
                        <li class="p-12 text-center">
                            <div class="mx-auto h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-inbox"></i></div>
                            <p class="mt-3 text-sm font-bold text-ink-900">Belum ada permohonan izin</p>
                            <p class="text-sm text-ink-500">Permohonan baru akan tampil di sini.</p>
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- chart --}}
            <div class="rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden">
                <div class="border-b border-ink-100 px-5 py-4">
                    <h3 class="text-sm font-extrabold text-ink-900">Statistik Jenis Perizinan</h3>
                    <p class="text-xs text-ink-500">Distribusi permohonan per jenis izin.</p>
                </div>
                <div class="p-4">
                    {!! $statistikJenisPerizinan->container() !!}
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ $statistikJenisPerizinan->cdn() }}"></script>
    {!! $statistikJenisPerizinan->script() !!}
@endpush
