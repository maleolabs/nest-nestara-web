<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="fixed top-0 w-full left-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="p-4">
        @if ($preview_izin_id)
            @livewire('dashboard.preview-izin.preview-data-izin', [
                'preview_izin_id' => $preview_izin_id,
                'preview_page' => 'dashboard.back-office.partials.rekom-form',
            ])
        @else
            @include('livewire.dashboard.back-office.partials.pendataan-entri-data')
        @endif
    </div>
    @script
    <script>
        window.nestaraRekomendasiRowClick ??= function (event) {
            const row = event.target.closest('.data-izin')
            if (!row) return
            const url = new URL(window.location)
            url.searchParams.set('preview_izin_id', row.dataset.izinId)
            window.history.pushState({}, '', url)
        }
        document.removeEventListener('click', window.nestaraRekomendasiRowClick)
        document.addEventListener('click', window.nestaraRekomendasiRowClick)
    </script>
    @endscript
</div>
