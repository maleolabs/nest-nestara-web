<div>

    {{-- loading indicator: pill kecil, fixed di tengah layar --}}
    <div wire:loading class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[80]" role="status" aria-live="polite">
        <div class="flex items-center gap-3 rounded-full bg-ink-900/90 pl-4 pr-5 py-3 shadow-[0_16px_40px_rgba(15,23,42,0.35)] text-white backdrop-blur-sm">
            <span class="relative flex h-5 w-5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-nest-400 opacity-60"></span>
                <i class="fa-solid fa-spinner fa-spin relative text-sm"></i>
            </span>
            <span class="text-sm font-bold tracking-tight">Memproses…</span>
        </div>
    </div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    @if ($preview_data_izin)
        @livewire('dashboard.preview-izin.preview-data-izin', ['preview_izin_id' => $preview_izin_id])
    @elseif ($preview_pemohon)
        @livewire('dashboard.pendaftaran.partials.preview-pemohon', ['pemohon_id' => $preview_pemohon_id])
    @else
        {{-- header + tabs --}}
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Layanan FO</p>
                <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Pendaftaran</h1>
                <p class="mt-1 text-sm text-ink-600">Kelola data pemohon & permohonan izin baru.</p>
            </div>
            <div class="flex rounded-full bg-white p-1 ring-1 ring-ink-200 shadow-sm">
                <button type="button" wire:click="selectMenu('{{ self::DATA_PEMOHON }}')" onclick="switchTab('{{ self::DATA_PEMOHON }}')"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold transition {{ $menu == self::DATA_PEMOHON ? 'bg-ink-900 text-white' : 'text-ink-600 hover:bg-ink-50' }}">
                    <i class="fa-solid fa-users text-xs"></i> Data Pemohon
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $menu == self::DATA_PEMOHON ? 'bg-white/15 text-white' : 'bg-nest-50 text-nest-700 ring-1 ring-nest-200' }}">{{ $semua_pemohon->total() }}</span>
                </button>
                <button type="button" wire:click="selectMenu('{{ self::DATA_IZIN_BARU }}')" onclick="switchTab('{{ self::DATA_IZIN_BARU }}')"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold transition {{ $menu == self::DATA_IZIN_BARU ? 'bg-ink-900 text-white' : 'text-ink-600 hover:bg-ink-50' }}">
                    <i class="fa-solid fa-file-circle-plus text-xs"></i> Izin Baru
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $menu == self::DATA_IZIN_BARU ? 'bg-white/15 text-white' : 'bg-nest-50 text-nest-700 ring-1 ring-nest-200' }}">{{ $semua_izin->total() }}</span>
                </button>
            </div>
        </div>

        {{-- filter rounded-2xl border border-ink-200 bg-white shadow-sm --}}
        <div class="rounded-2xl bg-white p-4 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[180px_180px_1fr]">
                <div>
                    <label for="start_date" class="text-xs font-bold tracking-wide text-ink-500 uppercase">Dari Tanggal</label>
                    <input type="date" id="start_date" name="start_date" wire:model.live.debounce.300ms='start_date'
                        class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                </div>
                <div>
                    <label for="end_date" class="text-xs font-bold tracking-wide text-ink-500 uppercase">Sampai Tanggal</label>
                    <input type="date" id="end_date" name="end_date" wire:model.live.debounce.300ms='end_date'
                        class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                </div>
                <div>
                    <label for="search" class="text-xs font-bold tracking-wide text-ink-500 uppercase">Cari</label>
                    <div class="relative mt-1.5">
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-sm"></i>
                        <input type="text" id="search" wire:model.live.debounce.400ms='search'
                            placeholder="{{ $menu == self::DATA_PEMOHON ? 'ID atau nama pemohon…' : 'Jenis izin, ID, atau nama pemohon…' }}"
                            class="w-full rounded-xl border border-ink-200 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                    </div>
                </div>
            </div>
        </div>

        {{-- table --}}
        <div class="mt-4 overflow-hidden rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
            @include('livewire.dashboard.pendaftaran.partials.table')
        </div>
    @endif
</div>


@push('scripts')
    <script>
        $('.data-izin').each(function(index, elemen) {
            $(elemen).on('click', function() {
                const izinId = $(this).attr('data-izin-id')
                let url = new URL(window.location)
                url.searchParams.set('preview_izin_id', izinId)
                window.history.pushState({}, '', url)
            })
        });

        $('.data-pemohon').each(function(index, elemen) {
            $(elemen).on('click', function() {
                const pemohonId = $(this).attr('data-pemohon-id')
                let url = new URL(window.location)
                url.searchParams.set('preview_pemohon_id', pemohonId)
                window.history.pushState({}, '', url)
            })
        });

        function switchTab(tab) {
            let url = new URL(window.location)
            url.searchParams.set('tab', tab)
            window.history.pushState({}, '', url)
        }
    </script>
@endpush
