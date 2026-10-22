@extends('dashboard.app')
@push('breadcrumb-item')
    <li class="text-xs font-semibold text-ink-600">/ Jenis Perizinan</li>
@endpush
@push('nav-dashboard-content')
    <div class="flex items-center gap-2">
        {{-- Filter dropdown --}}
        <div x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false" class="relative" id="filterDropdownWrap">
            <button type="button" id="filterBtn"
                class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-4 py-2 text-sm font-bold text-ink-700 hover:border-nest-300 hover:bg-nest-50 transition"
                aria-haspopup="true" @click="open = !open" :aria-expanded="open.toString()">
                <i class="fa-solid fa-filter text-xs text-nest-700"></i>
                <span id="filterBtnLabel">Semua</span>
                <i class="fa-solid fa-chevron-down text-[10px] text-ink-400"></i>
            </button>
            <div id="filterMenu" x-show="open" x-cloak
                class="absolute right-0 top-[calc(100%+8px)] w-52 rounded-2xl bg-white shadow-[0_16px_40px_rgba(15,23,42,0.14)] ring-1 ring-ink-200 p-1.5 z-50 animate-fadeIn">
                <button type="button" data-filter="semua"
                    class="filter-option w-full flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-ink-50 transition">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-layer-group w-4 text-ink-400"></i> Semua</span>
                    <i class="fa-solid fa-check text-xs text-nest-700 hidden filter-check"></i>
                </button>
                <button type="button" data-filter="tersedia"
                    class="filter-option w-full flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-ink-50 transition">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check w-4 text-emerald-500"></i> Tersedia</span>
                    <i class="fa-solid fa-check text-xs text-nest-700 hidden filter-check"></i>
                </button>
                <button type="button" data-filter="tidak_tersedia"
                    class="filter-option w-full flex items-center justify-between rounded-xl px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-ink-50 transition">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-circle-xmark w-4 text-red-400"></i> Tidak Tersedia</span>
                    <i class="fa-solid fa-check text-xs text-nest-700 hidden filter-check"></i>
                </button>
            </div>
        </div>

        {{-- Search with live preview --}}
        <div class="relative w-full sm:w-[320px] lg:w-[380px]">
            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-sm"></i>
            <input type="search" id="searchInput" autocomplete="off"
                data-search-url="{{ route('admin.jenis-perizinan.search-data') }}"
                placeholder="Cari jenis perizinan…"
                class="w-full rounded-full border border-ink-200 bg-white py-2 pl-10 pr-9 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 transition" />
            <button type="button" id="searchClear"
                class="hidden absolute right-2 top-1/2 -translate-y-1/2 h-6 w-6 rounded-full bg-ink-100 text-ink-500 grid place-items-center hover:bg-ink-200 transition">
                <i class="fa-solid fa-xmark text-[10px]"></i>
            </button>
            {{-- search preview panel --}}
            <div id="searchPreview"
                class="hidden absolute left-0 right-0 top-[calc(100%+8px)] max-h-[420px] overflow-y-auto rounded-2xl bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)] ring-1 ring-ink-200 z-50 animate-fadeIn"></div>
        </div>
    </div>
@endpush
@section('main')
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Master Data</p>
            <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Jenis Perizinan</h1>
            <p class="mt-1 text-sm text-ink-600">Kelola katalog izin — pilih item untuk mengatur detail, persyaratan, dan surat.</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-semibold text-ink-500">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 ring-1 ring-ink-200"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Tersedia = template siap</span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 ring-1 ring-ink-200"><span class="h-2 w-2 rounded-full bg-red-400"></span> Belum ada template</span>
        </div>
    </div>

    <div id="data-perizinan-container" data-load-url="{{ route('admin.jenis-perizinan.load-data') }}">
        <div id="dataRow" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4"></div>
    </div>

    <div id="loading-message" class="hidden">
        <div id="loadingRow" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4"></div>
    </div>

    <div id="emptyState" class="hidden mt-2 rounded-2xl border border-dashed border-ink-200 bg-white p-12 text-center">
        <div class="mx-auto h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-inbox"></i></div>
        <p class="mt-3 text-sm font-bold text-ink-900">Tidak ada data pada filter ini</p>
        <p class="text-sm text-ink-500">Coba ganti filter atau muat ulang halaman.</p>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/paginate.js') }}"></script>
@endpush
