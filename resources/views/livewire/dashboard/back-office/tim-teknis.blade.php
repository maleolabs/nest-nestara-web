<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="dash-menu sticky-top-nav px-4">
        <div class="dash-menu-item{{ $active_menu == self::HASIL_TINJAUAN ? ' active' : '' }}" wire:click="selectMenu('{{ self::HASIL_TINJAUAN }}')" onclick="switchTab('{{ self::HASIL_TINJAUAN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid me-2"></i>
                <span>{{ self::HASIL_TINJAUAN }}</span>
                <span class="ms-2 badge rounded-pill bg-app-primary-light">9+</span>
            </span>
        </div>
        <div class="dash-menu-item{{ $active_menu == self::BERITA_ACARA ? ' active' : '' }}" wire:click="selectMenu('{{ self::BERITA_ACARA }}')" onclick="switchTab('{{ self::BERITA_ACARA }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid me-2"></i>
                <span>{{ self::BERITA_ACARA }}</span>
                <span class="ms-2 badge rounded-pill bg-app-primary-light">9+</span>
            </span>
        </div>
        <div class="dash-menu-item{{ $active_menu == self::REKOMENDASI ? ' active' : '' }}" wire:click="selectMenu('{{ self::REKOMENDASI }}')" onclick="switchTab('{{ self::REKOMENDASI }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid me-2"></i>
                <span>{{ self::REKOMENDASI }}</span>
                <span class="ms-2 badge rounded-pill bg-app-primary-light">9+</span>
            </span>
        </div>
    </div>
    <div class="content-wrap p-4">
        @if ($preview_izin_id)
            @if ($active_menu == self::HASIL_TINJAUAN)
                @livewire('dashboard.preview-izin.preview-data-izin', [
                    'preview_izin_id' => $preview_izin_id,
                    'preview_page' => 'dashboard.back-office.verifikasi',
                ])
            @elseif($active_menu == self::BERITA_ACARA)
                @livewire('dashboard.back-office.berita-acara')
            @elseif($active_menu == self::REKOMENDASI)
                @livewire('dashboard.back-office.rekomendasi')
            @endif
        @else
            @include('livewire.dashboard.back-office.partials.pendataan-entri-data')
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
        $('.data-izin').each(function(index, elemen) {
            $(elemen).on('click', function() {
                const izinId = $(this).attr('data-izin-id')
                let url = new URL(window.location)
                url.searchParams.set('preview_izin_id', izinId)
                window.history.pushState({}, '', url)
            })
        });
    </script>
@endpush