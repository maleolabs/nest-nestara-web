<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    {{-- header + tab pill --}}
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Back Office</p>
            <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Penetapan</h1>
            <p class="mt-1 text-sm text-ink-600">Tindakan penetapan & penerbitan surat keputusan.</p>
        </div>
        <div class="flex flex-wrap rounded-full bg-white p-1 ring-1 ring-ink-200 shadow-sm">
            @foreach ([['tab' => self::PENETAPAN, 'icon' => 'fa-stamp'], ['tab' => self::PEMBUATAN, 'icon' => 'fa-print'], ['tab' => self::LAYANAN_DITOLAK, 'icon' => 'fa-file-circle-xmark']] as $t)
                <button type="button" wire:click="selectMenu('{{ $t['tab'] }}')" onclick="switchTab('{{ $t['tab'] }}')"
                    role="tab" aria-selected="{{ $active_menu == $t['tab'] ? 'true' : 'false' }}"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-bold transition {{ $active_menu == $t['tab'] ? 'bg-ink-900 text-white' : 'text-ink-600 hover:bg-ink-50' }}">
                    <i class="fa-solid {{ $t['icon'] }} text-xs"></i> {{ $t['tab'] }}
                    @if (isset($count_tabs[$t['tab']]) && $count_tabs[$t['tab']] > 0)
                        <span class="rounded-full px-2 py-0.5 text-[11px] font-extrabold {{ $active_menu == $t['tab'] ? 'bg-white/15 text-white' : 'bg-nest-50 text-nest-700 ring-1 ring-nest-200' }}">{{ $count_tabs[$t['tab']] }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <div>
        @if ($preview_izin_id)
            @livewire('dashboard.preview-izin.preview-data-izin', ['preview_izin_id' => $preview_izin_id])
        @else
            @include('livewire.dashboard.back-office.partials.penetapan-izin')
        @endif
    </div>
</div>
@push('scripts')
    <script>
        function switchTab(tab) {
            let url = new URL(window.location)
            url.searchParams.set('tab', tab)
            window.history.pushState({}, '', url)
        }
    </script>
@endpush
