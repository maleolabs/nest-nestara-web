<div>
    <md-linear-progress wire:loading wire:target='saveDataPemohon' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    {{-- header --}}
    <div class="mb-5">
        <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Pendaftaran • Data Pemohon</p>
        <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Formulir Pemohon Baru</h1>
        <p class="mt-1 text-sm text-ink-600">Lengkapi identitas & kontak pemohon — data akan dipakai pada penerbitan surat izin.</p>
    </div>

    <div class="rounded-2xl bg-white p-6 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
        @if ($saved)
            @include('livewire.dashboard.pendaftaran.partials.data-pemohon-readonly')
        @else
            @include('livewire.dashboard.pendaftaran.partials.data-pemohon-form')
        @endif
    </div>
</div>
