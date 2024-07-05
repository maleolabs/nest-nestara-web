<div>
    <md-linear-progress wire:loading wire:target='save' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    @if ($saved)
        @include('livewire.dashboard.pendaftaran.partials.data-pemohon-readonly')
    @else
        @include('livewire.dashboard.pendaftaran.partials.data-pemohon-form')
    @endif
</div>