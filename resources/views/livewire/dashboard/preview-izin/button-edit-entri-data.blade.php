<div class="mb-3 flex items-center gap-3">
    <p class="text-sm">Anda sudah melakukan entri data pada {{ $last_izin_status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA.' }}</p>
    @if (!$edit_entri_data)
        @if ($this->canEditEntriData())
            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs bg-ink-100 text-ink-800 hover:bg-ink-200" type="button" wire:click='editEntriData'>
                edit
                <i class="fa-solid fa-pencil ml-1" wire:loading.remove wire:target='editEntriData'></i>
                <div class="animate-spin h-4 w-4 rounded-full border-2 border-current border-t-transparent ml-1" role="status" wire:loading wire:target='editEntriData'>
                    <span class="sr-only">Memuat</span>
                </div>
            </button>
        @else
            <span class="text-red-600 text-sm">
                <i class="fa-solid fa-circle-exclamation mr-1"></i>
                Anda tidak mempunyai hak akses untuk mengubah data ini!
            </span>
        @endif
    @endif
</div>
