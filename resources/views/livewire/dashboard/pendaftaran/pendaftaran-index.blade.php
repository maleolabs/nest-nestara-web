<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="dash-menu sticky-top-nav container-fluid">
        <div class="dash-menu-item {{ $menu == self::DATA_PEMOHON ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PEMOHON }}')" onclick="switchTab('{{ self::DATA_PEMOHON }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-users me-2"></i>
                <span>{{ self::DATA_PEMOHON }}</span>
                @if (count($semua_pemohon))
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ count($semua_pemohon) }}</span>
                @endif
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_IZIN_BARU ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_IZIN_BARU }}')" onclick="switchTab('{{ self::DATA_IZIN_BARU }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-circle-plus me-2"></i>
                <span>{{ self::DATA_IZIN_BARU }}</span>
                @if (count($semua_izin))
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ count($semua_izin) }}</span>
                @endif
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_PERPANJANGAN_IZIN ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PERPANJANGAN_IZIN }}')" onclick="switchTab('{{ self::DATA_PERPANJANGAN_IZIN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-arrow-up me-2"></i>
                <span>{{ self::DATA_PERPANJANGAN_IZIN }}</span>
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_PERUBAHAN_IZIN ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PERUBAHAN_IZIN }}')" onclick="switchTab('{{ self::DATA_PERUBAHAN_IZIN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-edit me-2"></i>
                <span>{{ self::DATA_PERUBAHAN_IZIN }}</span>
            </span>
        </div>
    </div>
    <div class="content-wrap py-3">
        @if ($preview_data_izin)
            @livewire('dashboard.preview-izin.preview-data-izin', ['preview_izin_id' => $preview_izin_id])
        @else
            <div class="px-2">
                <div class="card min-vh-50 p-3">
                    <div class="d-flex align-items-end gap-3 mb-4">
                        <div>
                            <label for="start_date" class="form-label mb-0">Dari Tanggal</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" id="start_date" name="start_date" wire:model.live='start_date'>
                            </div>
                        </div>
                        <div>
                            <label for="end_date" class="form-label mb-0">Sampai Tanggal</label>
                            <div class="input-group input-group-outline">
                                <input type="date" class="form-control" id="end_date" name="end_date" wire:model.live='end_date'>
                            </div>
                        </div>
                        <div class="input-group input-group-outline">
                            <label class="form-label">Cari {{ $menu == self::DATA_PEMOHON ? 'ID atau nama pemohon...' : 'jenis izin, ID, atau nama pemohon...' }}</label>
                            <input type="text" class="form-control" wire:model.live='search'>
                        </div>
                    </div>
                    @if ($layout == 'grid')
                        @include('livewire.dashboard.pendaftaran.partials.grid')
                    @else
                        @include('livewire.dashboard.pendaftaran.partials.table')
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@push('scripts')
    <script>
        const btnCloseModal = $('#btn-close-modal')
        if (btnCloseModal) {
            Livewire.dispatch('refresh-component')
        }

        $('.data-izin').each(function(index, elemen) {
            $(elemen).on('click', function() {
                const izinId = $(this).attr('data-izin-id')
                let url = new URL(window.location)
                url.searchParams.set('preview_izin_id', izinId)
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