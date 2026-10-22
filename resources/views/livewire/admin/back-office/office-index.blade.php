<div>
    <md-linear-progress wire:loading wire:target='selectMenu' class="fixed top-0 w-full left-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="flex flex-wrap gap-2 border-b border-ink-200 pb-2">
        <div class="rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer {{ $menu == self::PENDATAAN ? 'active' : '' }}" wire:click="selectMenu('{{ self::PENDATAAN }}')" onclick="switchTab('{{ self::PENDATAAN }}')">
            <span class="rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer-content">
                <i class="fa-solid fa-file-pen mr-2"></i>
                <span>{{ self::PENDATAAN }}</span>
                {{-- @if (count($semua_pemohon))
                    <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-nest-50 text-nest-700 ring-1 ring-nest-200">{{ count($semua_pemohon) }}</span>
                @endif --}}
            </span>
        </div>
        <div class="rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer {{ $menu == self::TIM_TEKNIS ? 'active' : '' }}" wire:click="selectMenu('{{ self::TIM_TEKNIS }}')" onclick="switchTab('{{ self::TIM_TEKNIS }}')">
            <span class="rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer-content">
                <i class="fa-solid fa-people-group mr-2"></i>
                <span>{{ self::TIM_TEKNIS }}</span>
                {{-- @if (count($semua_izin))
                    <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-nest-50 text-nest-700 ring-1 ring-nest-200">{{ count($semua_izin) }}</span>
                @endif --}}
            </span>
        </div>
        <div class="rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer {{ $menu == self::PENETAPAN ? 'active' : '' }}" wire:click="selectMenu('{{ self::PENETAPAN }}')" onclick="switchTab('{{ self::PENETAPAN }}')">
            <span class="rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer-content">
                <i class="fa-solid fa-file-export mr-2"></i>
                <span>{{ self::PENETAPAN }}</span>
            </span>
        </div>
    </div>
    <div class="mt-4">@if ($menu == self::PENDATAAN) @include("livewire.dashboard.back-office.pendataan") @elseif ($menu == self::TIM_TEKNIS) @include("livewire.dashboard.back-office.rekomendasi") @else @include("livewire.dashboard.back-office.penetapan") @endif</div>
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
