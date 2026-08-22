<div>
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="font-display font-extrabold text-[18px] text-ink-900 leading-tight">Tindakan Perizinan</h2>
            <p class="text-xs text-ink-500">Aksi administratif pada jenis izin ini.</p>
        </div>
    </div>

    <div class="mt-5 space-y-4">
        {{-- ubah nama --}}
        <div class="rounded-xl border border-ink-200 p-4">
            @if ($editing_name)
                <form wire:submit.prevent='saveNamaJenis'>
                    <p class="text-sm font-bold text-ink-900">Ganti Nama Jenis Perizinan</p>
                    <input type="text" wire:model.lazy='nama_jenis' placeholder="Masukkan nama jenis perizinan baru"
                        class="mt-3 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                    <div class="mt-3 flex items-center gap-2">
                        <button type="button" wire:click='editNamaJenis'
                            class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 text-xs font-bold text-ink-700 hover:bg-ink-50 transition"
                            wire:loading.attr="disabled" wire:target='editNamaJenis'>
                            <span wire:loading.remove wire:target='editNamaJenis'>Batal</span>
                            <span wire:loading wire:target='editNamaJenis'><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-full bg-nest-700 px-4 py-2 text-xs font-bold text-white hover:bg-nest-800 transition disabled:opacity-50"
                            wire:loading.attr="disabled" wire:target='saveNamaJenis'>
                            <span wire:loading.remove wire:target='saveNamaJenis'><i class="fa-solid fa-floppy-disk"></i> Simpan</span>
                            <span wire:loading wire:target='saveNamaJenis'><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </form>
            @else
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-ink-900">Ubah nama perizinan “{{ str_capitalize($preview_jenis->nama, true) }}”</p>
                        <p class="mt-0.5 max-w-[60ch] text-xs leading-relaxed text-ink-500">Perubahan nama dapat berdampak pada proses administrasi terkait. Pertimbangkan dengan matang sebelum melanjutkan.</p>
                    </div>
                    <button type="button" wire:click='editNamaJenis'
                        class="shrink-0 inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 text-xs font-bold text-ink-700 hover:border-nest-300 hover:bg-nest-50 transition"
                        wire:loading.attr="disabled" wire:target='editNamaJenis'>
                        <span wire:loading.remove wire:target='editNamaJenis'><i class="fa-solid fa-pen"></i> Ubah</span>
                        <span wire:loading wire:target='editNamaJenis'><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                </div>
            @endif
        </div>

        {{-- hapus jenis --}}
        <div class="rounded-xl border border-red-200 bg-red-50/40 p-4">
            @if ($deleting)
                <p class="text-sm font-bold text-red-700"><i class="fa-solid fa-triangle-exclamation mr-1.5"></i>Konfirmasi Penghapusan</p>
                <p class="mt-1 text-xs text-red-700/80">Tindakan permanen — jenis perizinan dan relasinya tidak dapat dipulihkan.</p>
                <div class="mt-3 flex items-center gap-2">
                    <button type="button" wire:click='deleteJenis'
                        class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 text-xs font-bold text-ink-700 hover:bg-ink-50 transition"
                        wire:loading.attr="disabled" wire:target='deleteJenis'>
                        <span wire:loading.remove wire:target='deleteJenis'>Batal</span>
                        <span wire:loading wire:target='deleteJenis'><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                    <button type="button" wire:click='confirmDelete'
                        class="inline-flex items-center gap-1.5 rounded-full bg-red-600 px-4 py-2 text-xs font-extrabold text-white hover:bg-red-700 transition disabled:opacity-50"
                        wire:loading.attr="disabled" wire:target='confirmDelete'>
                        <span wire:loading.remove wire:target='confirmDelete'><i class="fa-solid fa-trash"></i> Ya, Hapus Permanen</span>
                        <span wire:loading wire:target='confirmDelete'><i class="fa-solid fa-spinner fa-spin"></i></span>
                    </button>
                </div>
            @else
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-red-700"><i class="fa-solid fa-trash mr-1.5"></i>Hapus Jenis Perizinan</p>
                        <p class="mt-0.5 text-xs text-red-700/70">Penghapusan bersifat permanen dan tidak dapat dibatalkan.</p>
                    </div>
                    <button type="button" wire:click='$set("deleting", true)'
                        class="shrink-0 inline-flex items-center gap-1.5 rounded-full border border-red-300 bg-white px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
