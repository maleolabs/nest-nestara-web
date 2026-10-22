<div class="mb-3 flex items-center gap-3">
    <p class="text-sm">{{ App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI }}</p>
    @if (!$edit_entri_data)
        @if ($this->thereIsOldEntriData())
            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs bg-ink-100 text-ink-800 hover:bg-ink-200" type="button" wire:click='getOldEntriData'>
                ambil data lama yang sesuai
                <i class="fa-solid fa-pencil ml-1" wire:loading.remove wire:target='getOldEntriData'></i>
                <div class="animate-spin h-4 w-4 rounded-full border-2 border-current border-t-transparent ml-1" role="status" wire:loading wire:target='getOldEntriData'>
                    <span class="sr-only">Memuat</span>
                </div>
            </button>
        @endif
    @endif
</div>
