@push('breadcrumb-item')
    <li class="text-xs font-semibold text-ink-600">/ <a href="{{ route('admin.jenis-perizinan.index') }}" class="hover:text-nest-700">Jenis Perizinan</a></li>
    <li class="text-xs font-bold text-nest-700">{{ Str::limit(str_capitalize($preview_jenis->nama, true), 70) }}</li>
@endpush
<div class="row">
    <md-linear-progress wire:loading wire:target='selectTab' class="fixed top-0 left-0 w-full" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    @php
        $tabs = [
            [self::DETAIL_TAB, 'Detail Perizinan', 'fa-file-lines'],
            [self::PERSYARATAN_TAB, 'Persyaratan', 'fa-list-check'],
            [self::DATA_ENTRI_TAB, 'Properti Data Entri', 'fa-table-cells'],
            [self::PENGATURAN_SURAT_TAB, 'Pengaturan Surat', 'fa-file-signature'],
            [self::TINDAKAN_TAB, 'Tindakan Perizinan', 'fa-gears'],
        ];
    @endphp
    <div class="grid gap-6 lg:grid-cols-[280px_1fr] items-start">
        <aside class="h-fit rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden lg:sticky lg:top-[88px]">
            <div class="border-b border-ink-100 px-5 py-4">
                <h3 class="text-sm font-extrabold text-ink-900 leading-tight">{{ Str::limit(str_capitalize($preview_jenis->nama, true), 40) }}</h3>
                <p class="text-xs text-ink-500">Kelola aspek perizinan</p>
            </div>
            <nav class="p-2 space-y-1">
                @foreach ($tabs as [$tab, $label, $icon])
                    <button type="button" wire:click="selectTab('{{ $tab }}')" onclick="switchTab('{{ $tab }}')"
                        class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $current_tab == $tab ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
                        <span class="h-8 w-8 grid place-items-center rounded-lg {{ $current_tab == $tab ? 'bg-white/15' : 'bg-ink-100' }}"><i class="fa-solid {{ $icon }} text-xs"></i></span>
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </aside>

        <section class="min-h-[400px] rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
            <div class="p-6">
                @switch($current_tab)
                    @case(self::DETAIL_TAB)
                        @livewire('admin.jenis-perizinan.detail-perizinan', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::PERSYARATAN_TAB)
                        @livewire('admin.jenis-perizinan.persyaratan-izin', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::DATA_ENTRI_TAB)
                        @livewire('admin.jenis-perizinan.properti-data-entri', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::PENGATURAN_SURAT_TAB)
                        @livewire('admin.jenis-perizinan.pengaturan-surat', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::TINDAKAN_TAB)
                        @livewire('admin.jenis-perizinan.tindakan-perizinan', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @default
                        
                @endswitch      
            
            </div>
        </section>
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