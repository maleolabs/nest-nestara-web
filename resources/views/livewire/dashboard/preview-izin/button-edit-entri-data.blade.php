<div class="d-flex align-items-center gap-3 mb-3">
    <p class="text-sm mb-0">Anda sudah melakukan entri data pada {{ $last_izin_status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA.' }}</p>
    @if (!$edit_entri_data)
        @if ($this->canEditEntriData())
            <button class="btn btn-sm btn-secondary" type="button" wire:click='editEntriData'>
                edit
                <i class="fa-solid fa-pencil ms-1" wire:loading.remove wire:target='editEntriData'></i>
                <div class="spinner-border spinner-border-sm ms-1" role="status" wire:loading wire:target='editEntriData'>
                    <span class="visually-hidden">Loading...</span>
                </div>
            </button>
        @else
            <span class="text-danger text-sm">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                Anda tidak mempunyai hak akses untuk mengubah data ini!
            </span>
        @endif
    @endif
</div>