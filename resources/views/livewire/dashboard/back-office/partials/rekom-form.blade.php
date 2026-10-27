<div>
    <md-linear-progress wire:loading wire:target='save, cancel' class="fixed top-0 w-full left-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="mx-auto max-w-5xl rounded-2xl border border-ink-200 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-ink-200 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div><p class="text-xs font-bold uppercase tracking-[.14em] text-nest-700">Form keputusan</p><h1 class="text-xl font-bold text-ink-950">Rekomendasi permohonan izin</h1></div>
            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">Menunggu rekomendasi</span>
        </div>
        <div class="space-y-5 p-4 sm:p-6">
            <section class="grid gap-4 rounded-xl bg-ink-50 p-4 sm:grid-cols-3" aria-label="Ringkasan permohonan">
                <div><p class="text-xs text-ink-500">Jenis izin</p><p class="font-bold text-ink-900">{{ str_capitalize($preview_izin->jenis_perizinan->nama, true) }}</p></div>
                <div><p class="text-xs text-ink-500">Pemohon</p><p class="font-bold text-ink-900">{{ str_capitalize($preview_izin->pemohon->nama_pemohon, true) }}</p></div>
                <div><p class="text-xs text-ink-500">Lokasi</p><p class="font-bold text-ink-900">{{ str_capitalize($preview_izin->lokasi_izin, true) }}</p></div>
            </section>
            <div class="rounded-xl bg-nest-50 p-3 text-sm text-nest-800 ring-1 ring-nest-200" aria-live="polite">
                @if ($saved)
                    <i class="fa-solid fa-circle-check ml-1"></i>
                    Rekomendasi permohonan berhasil disimpan!
                @else
                    Pada tahapan rekomendasi permohonan izin, pegawai harus menentukan status rekomendasi (direkomendasikan, tidak direkomendasikan, rekomendasi dengan catatan, atau perlu pembahasan lebih lanjut) dan mencatat jenis kegiatan yang dilakukan (pemeriksaan berkas, survei lapangan, atau rapat tim teknis).
                @endif
            </div>
            <form wire:submit.prevent='save'>
                <fieldset class="mb-5"><legend class="mb-2 text-sm font-bold text-ink-900">Hasil rekomendasi</legend><div class="grid gap-2 sm:grid-cols-2">
                    <div class="flex items-center gap-2">
                        <input type="radio" class="peer h-4 w-4 accent-nest-700" value="direkomendasikan" name="rekomendasi" id="direkom" wire:model='rekomendasi'>
                        <label for="direkom" class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold text-ink-800 peer-checked:border-nest-600 peer-checked:bg-nest-50">Direkomendasikan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="peer h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::TIDAK_DIREKOM }}" name="rekomendasi" id="tidak-direkom" wire:model='rekomendasi'>
                        <label for="tidak-direkom" class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold text-ink-800 peer-checked:border-nest-600 peer-checked:bg-nest-50">Tidak direkomendasikan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="peer h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::REKOM_CATATAN }}" name="rekomendasi" id="rekom-bersyarat" wire:model='rekomendasi'>
                        <label for="rekom-bersyarat" class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold text-ink-800 peer-checked:border-nest-600 peer-checked:bg-nest-50">Direkomendasikan dengan catatan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="peer h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::BAHAS_LANJUT }}" name="rekomendasi" id="bahas-lanjut" wire:model='rekomendasi'>
                        <label for="bahas-lanjut" class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold text-ink-800 peer-checked:border-nest-600 peer-checked:bg-nest-50">Perlu pembahasan lebih lanjut</label>
                    </div>
                </div></fieldset>
                @error('rekomendasi')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
                <fieldset class="mb-5"><legend class="mb-2 text-sm font-bold text-ink-900">Aktivitas pemeriksaan</legend><div class="grid gap-2 sm:grid-cols-3">
                    <div class="flex items-center gap-2">
                        <input type="radio" class="peer h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::PERIKSA_BERKAS }}" name="jenis_kegiatan" id="periksa-berkas" wire:model='jenis_kegiatan'>
                        <label for="periksa-berkas" class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold peer-checked:border-nest-600 peer-checked:bg-nest-50">Pemeriksaan berkas</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="peer h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::SURVEY_LAPANGAN }}" name="jenis_kegiatan" id="survey-lapangan" wire:model='jenis_kegiatan'>
                        <label for="survey-lapangan" class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold peer-checked:border-nest-600 peer-checked:bg-nest-50">Survei lapangan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="peer h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::RAPAT_TIM }}" name="jenis_kegiatan" id="rapat-tim" wire:model='jenis_kegiatan'>
                        <label for="rapat-tim" class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold peer-checked:border-nest-600 peer-checked:bg-nest-50">Rapat tim teknis</label>
                    </div>
                </div></fieldset>
                @error('jenis_kegiatan')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
                <div class="flex flex-col-reverse gap-3 border-t border-ink-200 pt-4 sm:flex-row sm:justify-end">
                    <button class="inline-flex min-h-11 items-center justify-center rounded-xl bg-ink-100 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:bg-ink-200 disabled:cursor-not-allowed disabled:opacity-50" type="button" wire:click='cancel' wire:loading.attr="disabled">{{ $saved ? 'Kembali ke antrean' : 'Batal' }}</button>
                    <button class="inline-flex min-h-11 items-center justify-center rounded-xl bg-nest-700 px-5 py-2 text-sm font-semibold text-white transition hover:bg-nest-800 disabled:cursor-not-allowed disabled:opacity-50" type="submit" wire:loading.attr="disabled"><span wire:loading.remove wire:target="save">Simpan rekomendasi</span><span wire:loading wire:target="save">Menyimpan…</span></button>
                </div>
            </form>
        </div>
    </div>
</div>
