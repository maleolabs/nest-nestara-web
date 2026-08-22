<div>
    <div class="flex items-center justify-between gap-3">
    <div>
        <h2 class="font-display font-extrabold text-[18px] text-ink-900 leading-tight">Pengaturan Surat</h2>
        <p class="text-xs text-ink-500">Format penomoran & template surat untuk jenis izin ini.</p>
    </div>
</div>
<div class="mt-2 divide-y divide-ink-100">
            <div class="grid gap-2 py-5 sm:grid-cols-[220px_1fr] sm:items-center">
            <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Format Surat</p>
            <p class="text-sm font-bold text-ink-900">{{ $full_format_surat }}</p>
        </div>
        <div class="grid gap-2 py-5 sm:grid-cols-[220px_1fr] sm:items-center">
            <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Format Jenis Surat</p>
            <div class="flex items-center gap-3 flex-wrap"><input type="text" wire:model.lazy='format_surat' class="w-full sm:w-64 rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='format_surat'></i>
                @if ($format_surat != $old_pengaturan_form['format_surat'])
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600"><i class="fa-solid fa-circle-check"></i> Berhasil disimpan</span>
                @endif
            </div>
        </div>
        <div class="grid gap-2 py-5 sm:grid-cols-[220px_1fr] sm:items-center">
            <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Nomor Terakhir Jenis Surat</p>
            <div class="flex items-center gap-3 flex-wrap"><input type="number" wire:model.lazy='nomor_jenis_surat' class="w-24 rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='nomor_jenis_surat'></i>
                @if ($nomor_jenis_surat != $old_pengaturan_form['nomor_jenis_surat'])
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600"><i class="fa-solid fa-circle-check"></i> Berhasil disimpan</span>
                @endif
            </div>
        </div>
        <div class="grid gap-2 py-5 sm:grid-cols-[220px_1fr] sm:items-center">
            <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Template Surat</p>
            <div class="flex items-center gap-3 flex-wrap">
                <select wire:model.lazy='selected_format'
                    class="w-full sm:w-64 rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                    @foreach ($template_surat as $template)
                        <option value="{{ $template }}">{{ $template }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='selected_template'></i>
                @if ($selected_template != $old_pengaturan_form['selected_template'])
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600"><i class="fa-solid fa-circle-check"></i> Berhasil disimpan</span>
                @endif
            </div>
        </div>

        <div class="grid gap-2 py-5 sm:grid-cols-[220px_1fr]">
            <span></span>
            <div class="pt-1">
                <p class="text-xs font-semibold text-ink-600">
                    <span class="mr-1.5 inline-flex items-center rounded-full bg-ink-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-ink-600">Default</span>
                    {{ $selected_template }}
                    @if (!$can_use_template)
                        <span class="ml-1 font-bold text-red-600">(Template pdf ini tidak bisa digunakan)</span>
                    @endif
                </p>
                <p class="mt-2 flex items-center gap-1.5 text-xs text-ink-400">
                    <i class="fa-solid fa-circle-info"></i> Tekan <kbd class="rounded bg-white px-1.5 py-0.5 font-bold text-ink-600 ring-1 ring-ink-200">Enter</kbd> pada kolom untuk menyimpan.
                </p>
            </div>
        </div>
    </div>
</div>