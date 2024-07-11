<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="dash-menu sticky-top-nav px-4">
        @foreach ($this->getMenu() as $menu => $icon)
            <div class="dash-menu-item{{ $active_menu == $menu ? ' active' : '' }}" wire:click="selectMenu('{{ $menu }}')" onclick="switchTab('{{ $menu }}')">
                <span class="dash-menu-item-content">
                    <i class="{{ $icon }} me-2"></i>
                    <span>{{ $menu }}</span>
                    @if ($count_izin[$menu] > 0)
                        <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ $count_izin[$menu] }}</span>
                    @endif
                </span>
            </div>
        @endforeach
    </div>
    <div class="content-wrap p-4">
        @if ($preview_izin_id)
            @livewire('dashboard.preview-izin.preview-data-izin', ['preview_izin_id' => $preview_izin_id])
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