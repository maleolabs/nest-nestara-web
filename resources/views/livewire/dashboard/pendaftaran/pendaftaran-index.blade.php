<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="dash-menu sticky-top-0">
        <div class="dash-menu-item {{ $menu == self::DATA_PEMOHON ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PEMOHON }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-users me-2"></i>
                <span>{{ self::DATA_PEMOHON }}</span>
                <span class="ms-2 badge rounded-pill bg-app-primary-light">12</span>
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_IZIN_BARU ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_IZIN_BARU }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-circle-plus me-2"></i>
                <span>{{ self::DATA_IZIN_BARU }}</span>
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_PERPANJANGAN_IZIN ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PERPANJANGAN_IZIN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-arrow-up me-2"></i>
                <span>{{ self::DATA_PERPANJANGAN_IZIN }}</span>
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_PERUBAHAN_IZIN ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PERUBAHAN_IZIN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-edit me-2"></i>
                <span>{{ self::DATA_PERUBAHAN_IZIN }}</span>
            </span>
        </div>
    </div>
    <div class="content-wrap">
        <h1>{{ $menu }}</h1>
    </div>
</div>