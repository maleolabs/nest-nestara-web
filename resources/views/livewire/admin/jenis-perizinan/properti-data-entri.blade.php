<div>
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="font-display font-extrabold text-[18px] text-ink-900 leading-tight">Properti Data Entri</h2>
            <p class="text-xs text-ink-500">Field tambahan yang diisi pegawai saat proses entri data izin.</p>
        </div>
        @if (count($properti_data_entri) > 0 && !$new_form_active)
            <button type="button" wire:click='addNewDataProperti'
                class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-4 py-2 text-sm font-bold text-white hover:bg-nest-800 transition"
                wire:loading.attr="disabled" wire:target='addNewDataProperti'>
                <span wire:loading.remove wire:target='addNewDataProperti'><i class="fa-solid fa-plus text-xs"></i> Tambah Baru</span>
                <span wire:loading wire:target='addNewDataProperti'><i class="fa-solid fa-spinner fa-spin"></i></span>
            </button>
        @endif
    </div>

    <div class="mt-5 overflow-hidden rounded-xl ring-1 ring-ink-200">
        @if (count($properti_data_entri) > 0)
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-ink-200 bg-ink-50/60">
                        <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Nama</th>
                        <th class="px-4 py-3 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Tipe</th>
                        <th class="px-4 py-3 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Maksimal</th>
                        <th class="px-4 py-3 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Mandatori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($properti_data_entri as $data_entri)
                        <tr class="hover:bg-nest-50/40 transition">
                            <td class="px-4 py-3">
                                <input type="text" wire:model.lazy='properti_form.{{ $data_entri->id }}'
                                    class="w-full rounded-lg border border-transparent bg-transparent px-2 py-1.5 text-sm font-semibold text-ink-900 hover:border-ink-200 focus:border-nest-600 focus:bg-white focus:outline-none focus:ring-4 focus:ring-nest-100">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <select wire:model.lazy='properti_entri_tipe.{{ $data_entri->id }}' id="tipe-entri-{{ $data_entri->id }}"
                                    class="rounded-lg border border-ink-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-ink-800 focus:border-nest-600 focus:outline-none focus:ring-2 focus:ring-nest-100">
                                    <option value="text">Text</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="number">Number</option>
                                    <option value="date">Date</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" wire:model.lazy='properti_entri_max.{{ $data_entri->id }}'
                                    class="w-20 mx-auto rounded-lg border border-ink-200 bg-white px-2 py-1.5 text-center text-xs font-semibold text-ink-800 focus:border-nest-600 focus:outline-none focus:ring-2 focus:ring-nest-100">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" {{ $properti_entri_mandatori ? 'checked' : '' }} wire:model.live='properti_entri_mandatori.{{ $data_entri->id }}'
                                    class="h-4 w-4 rounded-lg border-ink-300 text-nest-700 focus:ring-nest-600">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4"></td></tr>
                    @endforelse
                </tbody>
            </table>
            <p class="flex items-center gap-1.5 border-t border-ink-100 bg-ink-50/50 px-4 py-2.5 text-xs text-ink-400">
                <i class="fa-solid fa-circle-info"></i> Tekan <kbd class="rounded-lg bg-white px-1.5 py-0.5 font-bold text-ink-600 ring-1 ring-ink-200">Enter</kbd> pada kolom untuk menyimpan.
            </p>
        @endif
    </div>

    @if (count($properti_data_entri) == 0 && !$new_form_active)
        <div class="mt-4 rounded-xl border border-dashed border-ink-200 bg-white p-8 text-center">
            <div class="mx-auto h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-table-cells"></i></div>
            <p class="mt-3 text-sm font-bold text-ink-900">Belum ada properti data entri</p>
            <button type="button" wire:click='addNewDataProperti'
                class="mt-4 inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-nest-800 transition"
                wire:loading.attr="disabled" wire:target='addNewDataProperti'>
                <span wire:loading.remove wire:target='addNewDataProperti'><i class="fa-solid fa-plus text-xs"></i> Tambah Properti</span>
                <span wire:loading wire:target='addNewDataProperti'><i class="fa-solid fa-spinner fa-spin"></i></span>
            </button>
        </div>
    @endif

    @if ($new_form_active)
        <div class="mt-5 rounded-xl border-2 border-dashed border-nest-300 bg-nest-50/40 p-5">
            <p class="text-sm font-extrabold capitalize text-ink-900">Formulir Properti Data Entri Baru</p>
            <form wire:submit.prevent='saveNewDataProperti' class="mt-4">
                <div class="grid gap-3 sm:grid-cols-[1fr_140px_120px_100px_auto] sm:items-end">
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nama</label>
                        <input type="text" wire:model='properti_data_form.nama' placeholder="Nama properti"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                    </div>
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Tipe</label>
                        <select wire:model='properti_data_form.tipe' id="tipe-entri"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="number">Number</option>
                            <option value="date">Date</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Maksimal</label>
                        <input type="number" wire:model='properti_data_form.max'
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                    </div>
                    <label class="flex items-center gap-2 pb-3 text-xs font-bold text-ink-700">
                        <input type="checkbox" wire:model.live='properti_data_form.mandatori' class="h-4 w-4 rounded-lg border-ink-300 text-nest-700 focus:ring-nest-600">
                        Mandatori
                    </label>
                    <div class="flex items-center gap-2 pb-0.5">
                        <button type="button" wire:click='addNewDataProperti'
                            class="inline-flex items-center justify-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2.5 text-xs font-bold text-ink-700 hover:bg-ink-50 transition"
                            wire:loading.attr="disabled" wire:target='addNewDataProperti'>
                            <span wire:loading.remove wire:target='addNewDataProperti'>Batal</span>
                            <span wire:loading wire:target='addNewDataProperti'><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 rounded-full bg-nest-700 px-4 py-2.5 text-xs font-extrabold text-white hover:bg-nest-800 transition disabled:opacity-50"
                            wire:loading.attr="disabled" wire:target='saveNewDataProperti'>
                            <span wire:loading.remove wire:target='saveNewDataProperti'><i class="fa-solid fa-floppy-disk"></i> Simpan</span>
                            <span wire:loading wire:target='saveNewDataProperti'><i class="fa-solid fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif
</div>
