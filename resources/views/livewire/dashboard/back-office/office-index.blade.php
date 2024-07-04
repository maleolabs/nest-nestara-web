<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="dash-menu sticky-top-0">
        <div class="dash-menu-item {{ $menu == self::PENDATAAN ? 'active' : '' }}" wire:click="selectMenu('{{ self::PENDATAAN }}')" onclick="switchTab('{{ self::PENDATAAN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-pen me-2"></i>
                <span>{{ self::PENDATAAN }}</span>
                {{-- @if (count($semua_pemohon))
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ count($semua_pemohon) }}</span>
                @endif --}}
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::TIM_TEKNIS ? 'active' : '' }}" wire:click="selectMenu('{{ self::TIM_TEKNIS }}')" onclick="switchTab('{{ self::TIM_TEKNIS }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-people-group me-2"></i>
                <span>{{ self::TIM_TEKNIS }}</span>
                {{-- @if (count($semua_izin))
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ count($semua_izin) }}</span>
                @endif --}}
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::PENETAPAN ? 'active' : '' }}" wire:click="selectMenu('{{ self::PENETAPAN }}')" onclick="switchTab('{{ self::PENETAPAN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-export me-2"></i>
                <span>{{ self::PENETAPAN }}</span>
            </span>
        </div>
    </div>
    <div class="content-wrap p-4">

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