<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="content-wrap p-4">
        @if ($preview_izin_id)
            @livewire('dashboard.preview-izin.preview-data-izin', [
                'preview_izin_id' => $preview_izin_id,
                'preview_page' => 'dashboard.back-office.partials.rekom-form',
            ])
        @else
            @include('livewire.dashboard.back-office.partials.pendataan-entri-data')
        @endif
    </div>
</div>
@push('scripts')
    <script>
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