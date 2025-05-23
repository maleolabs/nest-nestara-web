<div class="d-flex align-items-center gap-3 mb-3">
    <p class="text-sm mb-0">{{ App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI }}</p>
    @if (!$edit_entri_data)
        @if ($this->thereIsOldEntriData())
            <button class="btn btn-sm btn-secondary" type="button" wire:click='getOldEntriData'>
                ambil data lama yang sesuai
                <i class="fa-solid fa-pencil ms-1" wire:loading.remove wire:target='getOldEntriData'></i>
                <div class="spinner-border spinner-border-sm ms-1" role="status" wire:loading wire:target='getOldEntriData'>
                    <span class="visually-hidden">Loading...</span>
                </div>
            </button>
        @endif
    @endif
</div>