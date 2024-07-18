<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="dash-menu sticky-top-nav px-4">
        <div class="dash-menu-item{{ $active_menu == self::PENETAPAN ? ' active' : '' }}" wire:click="selectMenu('{{ self::PENETAPAN }}')" onclick="switchTab('{{ self::PENETAPAN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-stamp me-2"></i>
                <span>{{ self::PENETAPAN }}</span>
                @if ($count_tabs[self::PENETAPAN] > 0)
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ $count_tabs[self::PENETAPAN] }}</span>
                @endif
            </span>
        </div>
        <div class="dash-menu-item{{ $active_menu == self::PEMBUATAN ? ' active' : '' }}" wire:click="selectMenu('{{ self::PEMBUATAN }}')" onclick="switchTab('{{ self::PEMBUATAN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-print me-2"></i>
                <span>{{ self::PEMBUATAN }}</span>
                @if ($count_tabs[self::PEMBUATAN] > 0)
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ $count_tabs[self::PEMBUATAN] }}</span>
                @endif
            </span>
        </div>
        <div class="dash-menu-item{{ $active_menu == self::LAYANAN_DITOLAK ? ' active' : '' }}" wire:click="selectMenu('{{ self::LAYANAN_DITOLAK }}')" onclick="switchTab('{{ self::LAYANAN_DITOLAK }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-circle-xmark me-2"></i>
                <span>{{ self::LAYANAN_DITOLAK }}</span>
                @if ($count_tabs[self::LAYANAN_DITOLAK] > 0)
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ $count_tabs[self::LAYANAN_DITOLAK] }}</span>
                @endif
            </span>
        </div>
    </div>

    <div class="content-wrap p-4">
        @include('livewire.dashboard.back-office.partials.pendataan-entri-data')
    </div>
    @include('livewire.dashboard.back-office.partials.penetapan-izin')
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