<div class="space-y-5" wire:loading.class="cursor-wait">
    <md-linear-progress wire:loading wire:target='selectMenu' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    {{-- header + tab pill --}}
    <header class="flex flex-col gap-4 border-b border-ink-200 pb-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Back Office</p>
            <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Pendataan</h1>
            <p class="mt-1 text-sm text-ink-600">Verifikasi kelengkapan data & entri permohonan izin.</p>
        </div>
        <nav aria-label="Tahapan pendataan" class="flex w-full gap-1 overflow-x-auto rounded-xl bg-ink-50 p-1 ring-1 ring-ink-200 lg:w-auto">
            @foreach ($this->getMenu() as $menu => $icon)
                <button type="button" wire:click="selectMenu('{{ $menu }}')" onclick="switchTab('{{ $menu }}')"
                    aria-current="{{ $active_menu == $menu ? 'page' : 'false' }}"
                    class="inline-flex min-h-10 shrink-0 items-center gap-2 rounded-lg px-4 py-2 text-sm font-bold transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-nest-600 focus-visible:ring-offset-2 {{ $active_menu == $menu ? 'bg-ink-900 text-white shadow-sm' : 'text-ink-600 hover:bg-white' }}">
                    <i class="{{ $icon }} text-xs"></i> {{ ucfirst($menu) }}
                    <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $active_menu == $menu ? 'bg-white/15 text-white' : 'bg-nest-50 text-nest-700 ring-1 ring-nest-200' }}">{{ $count_izin[$menu] }}</span>
                </button>
            @endforeach
        </nav>
    </header>

    <main aria-live="polite">
        @if ($preview_izin_id)
            @livewire('dashboard.preview-izin.preview-data-izin', ['preview_izin_id' => $preview_izin_id])
        @else
            @include('livewire.dashboard.back-office.partials.pendataan-entri-data')
        @endif
    </main>
</div>
@push('scripts')
    <script>
        function switchTab(tab) {
            let url = new URL(window.location)
            url.searchParams.set('tab', tab)
            window.history.pushState({}, '', url)
        }
        window.nestaraPendataanRowClick ??= function (event) {
            const row = event.target.closest('.data-izin')
            if (!row) return
            const url = new URL(window.location)
            url.searchParams.set('preview_izin_id', row.dataset.izinId)
            window.history.pushState({}, '', url)
        }
        document.removeEventListener('click', window.nestaraPendataanRowClick)
        document.addEventListener('click', window.nestaraPendataanRowClick)
    </script>
@endpush
