<div>
    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="font-display font-extrabold text-[18px] text-ink-900 leading-tight">{{ str_capitalize($preview_jenis->nama, true) }}</h2>
            <p class="text-xs text-ink-500">Detail Perizinan — perubahan tersimpan otomatis per field.</p>
        </div>
    </div>

    <div class="mt-6 space-y-5">
        {{-- Kelompok Izin --}}
        <div class="grid gap-2 sm:grid-cols-[220px_1fr] sm:items-center">
            <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Kelompok Izin</p>
            <div class="flex items-center gap-3 flex-wrap">
                <select wire:model.lazy='detail_form.kelompok_izin'
                    class="w-full sm:w-64 rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                    <option value="izin tanpa retribusi">Izin Tanpa Retribusi</option>
                    <option value="izin dengan retribusi">Izin Dengan Retribusi</option>
                </select>
                <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='detail_form.kelompok_izin'></i>
                @if ($detail_form->kelompok_izin != $old_detail_form->kelompok_izin)
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                        <i class="fa-solid fa-circle-check"></i> Berhasil disimpan
                    </span>
                @endif
            </div>
        </div>

        {{-- Durasi --}}
        <div class="grid gap-2 sm:grid-cols-[220px_1fr] sm:items-center">
            <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Durasi Pengerjaan</p>
            <div class="flex items-center gap-3 flex-wrap">
                <input type="number" wire:model.lazy='detail_form.durasi'
                    class="w-24 rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                <span class="text-sm font-semibold text-ink-600">Hari</span>
                <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='detail_form.durasi'></i>
                @if ($detail_form->durasi != $old_detail_form->durasi)
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                        <i class="fa-solid fa-circle-check"></i> Berhasil disimpan
                    </span>
                @endif
            </div>
        </div>

        {{-- Masa Berlaku --}}
        <div class="grid gap-2 sm:grid-cols-[220px_1fr] sm:items-center">
            <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Masa Berlaku</p>
            <div class="flex items-center gap-3 flex-wrap">
                <input type="number" wire:model.lazy='detail_form.masa_berlaku'
                    class="w-24 rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                <span class="text-sm font-semibold text-ink-600">
                    Tahun{{ $detail_form->masa_berlaku === 0 ? ' — 0 artinya berlaku selamanya' : '' }}
                </span>
                <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='detail_form.masa_berlaku'></i>
                @if ($detail_form->masa_berlaku != $old_detail_form->masa_berlaku)
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                        <i class="fa-solid fa-circle-check"></i> Berhasil disimpan
                    </span>
                @endif
            </div>
        </div>

        {{-- hint --}}
        <p class="flex items-center gap-1.5 text-xs text-ink-400 pt-1">
            <i class="fa-solid fa-circle-info"></i> Tekan <kbd class="rounded bg-white px-1.5 py-0.5 font-bold text-ink-600 ring-1 ring-ink-200">Enter</kbd> pada kolom untuk menyimpan.
        </p>
    </div>
</div>
