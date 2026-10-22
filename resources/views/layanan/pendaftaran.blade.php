@extends('layanan.layouts.app')
@push('breadcrumb-item')
    <li class="text-xs font-semibold text-ink-600">/ Pendaftaran</li>
@endpush
@push('nav-dashboard-content')
    <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" class="relative" id="tambahDropdownWrap">
        <button type="button" id="navbarDropdownMenuLink2" @click="open = !open" :aria-expanded="open.toString()"
            class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-4 py-2 text-sm font-bold text-white shadow-[0_8px_20px_rgba(15,118,110,0.25)] hover:bg-nest-800 transition"
            aria-haspopup="true">
            <i class="fa-solid fa-square-plus text-xs"></i> Tambah
            <i class="fa-solid fa-chevron-down text-[10px] opacity-70"></i>
        </button>
        <div id="tambahDropdownMenu" x-show="open" x-cloak
            class="hidden absolute right-0 top-[calc(100%+8px)] w-56 rounded-2xl bg-white shadow-[0_16px_40px_rgba(15,23,42,0.14)] ring-1 ring-ink-200 p-1.5 z-50 animate-fadeIn">
            <a href="{{ route('layanan.pendaftaran.create', ['type' => 'data-pemohon']) }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-nest-50 hover:text-nest-800 transition">
                <span class="h-8 w-8 grid place-items-center rounded-lg bg-sky-50 text-sky-600"><i class="fa-solid fa-users text-xs"></i></span>
                Data Pemohon
            </a>
            <a href="{{ route('layanan.pendaftaran.create', ['type' => 'izin-baru']) }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-nest-50 hover:text-nest-800 transition">
                <span class="h-8 w-8 grid place-items-center rounded-lg bg-nest-50 text-nest-700"><i class="fa-solid fa-file-circle-plus text-xs"></i></span>
                Izin Baru
            </a>
        </div>
    </div>
@endpush
@section('main')
    @livewire('dashboard.pendaftaran.pendaftaran-index')
@endsection
@push('scripts')
    <script>
        let pendaftaranSubBread = document.getElementById("pendaftaran-sub-breadcrumb")
        Livewire.on('menu-selected', function (event) {
            if (pendaftaranSubBread) pendaftaranSubBread.textContent = event[0].menu
        })

    </script>
@endpush
