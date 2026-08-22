<form wire:submit.prevent='saveDataPemohon'>
    @csrf
    <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
        {{-- kolom form --}}
        <div class="space-y-5">
            @include('livewire.dashboard.pendaftaran.partials.data-pemohon-form-field')
        </div>

        {{-- sidebar info & aksi --}}
        <aside class="h-fit lg:sticky lg:top-[88px] space-y-4">
            <div class="rounded-2xl bg-ink-50 p-4 ring-1 ring-ink-200">
                <p class="text-sm font-extrabold text-ink-900"><i class="fa-solid fa-circle-info mr-1.5 text-nest-700"></i>Sebelum menyimpan</p>
                <ul class="mt-2 space-y-1.5 text-xs leading-relaxed text-ink-600">
                    <li class="flex gap-1.5"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Pastikan nama sesuai identitas resmi (KTP/SIM/Paspor).</li>
                    <li class="flex gap-1.5"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Nomor ID bersifat unik — satu pemohon satu nomor.</li>
                    <li class="flex gap-1.5"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Data masih dapat diubah setelah tersimpan.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-nest-700 px-5 py-3 text-sm font-extrabold text-white shadow-[0_10px_24px_rgba(15,118,110,0.28)] hover:bg-nest-800 transition disabled:opacity-50"
                    wire:loading.attr="disabled" wire:target='saveDataPemohon'>
                    <span wire:loading.remove wire:target='saveDataPemohon'><i class="fa-solid fa-floppy-disk"></i> Simpan Data Pemohon</span>
                    <span wire:loading wire:target='saveDataPemohon'><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan…</span>
                </button>
                <button type="button" wire:click='resetData'
                    class="w-full inline-flex items-center justify-center gap-2 rounded-full border border-ink-200 bg-white px-5 py-3 text-sm font-bold text-ink-700 hover:bg-ink-50 transition">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </aside>
    </div>
</form>
