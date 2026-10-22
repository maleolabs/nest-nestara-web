<div>
    <md-linear-progress wire:loading wire:target='save, cancel' class="fixed top-0 w-full left-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
        <div class="border-b border-ink-200 px-4 py-3">
            <h6 class="font-bold">
                <i class="fa-solid fa-medal ml-1"></i>
                Rekomendasi Permohonan Izin
            </h6>
        </div>
        <div class="p-4">
            <p class="mb-0 text-sm">Nama Izin</p>
            <h3 class="">{{ str_capitalize($preview_izin->jenis_perizinan->nama, true) }}</h3>
            <p class="mb-0 text-sm">Nama Pemohon</p>
            <h6 class="font-bold">{{ str_capitalize($preview_izin->pemohon->nama_pemohon) }}</h6>
            <p class="mb-0 text-sm">Lokasi Izin</p>
            <h6 class="font-bold">{{ str_capitalize($preview_izin->lokasi_izin) }}</h6>
            <hr>
            <div class="rounded-xl bg-nest-50 text-nest-700 ring-1 ring-nest-200 p-3 text-sm text-nest-700">
                @if ($saved)
                    <i class="fa-solid fa-circle-check ml-1"></i>
                    Rekomendasi permohonan berhasil disimpan!
                @else
                    Pada tahapan rekomendasi permohonan izin, pegawai harus menentukan status rekomendasi (direkomendasikan, tidak direkomendasikan, rekomendasi dengan catatan, atau perlu pembahasan lebih lanjut) dan mencatat jenis kegiatan yang dilakukan (pemeriksaan berkas, survei lapangan, atau rapat tim teknis).
                @endif
            </div>
            <form wire:submit.prevent='save'>
                <p class="text-sm">Rekomendasi</p>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <input type="radio" class="h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::DIREKOM }}" name="rekomendasi" id="direkom" wire:model='rekomendasi'>
                        <label for="direkom" class="flex items-center gap-2 text-nest-700 font-bold">Direkomendasikan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::TIDAK_DIREKOM }}" name="rekomendasi" id="tidak-direkom" wire:model='rekomendasi'>
                        <label for="tidak-direkom" class="flex items-center gap-2 text-nest-700 font-bold">TIdak Direkomendasikan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::REKOM_CATATAN }}" name="rekomendasi" id="rekom-bersyarat" wire:model='rekomendasi'>
                        <label for="rekom-bersyarat" class="flex items-center gap-2 text-nest-700 font-bold">Direkomendasikan Dengan Catatan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::BAHAS_LANJUT }}" name="rekomendasi" id="bahas-lanjut" wire:model='rekomendasi'>
                        <label for="bahas-lanjut" class="flex items-center gap-2 text-nest-700 font-bold">Perlu Pembahasan Lebih Lanjut</label>
                    </div>
                </div>
                @error('rekomendasi')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
                <p class="text-sm">Jenis Kegiatan</p>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <input type="radio" class="h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::PERIKSA_BERKAS }}" name="jenis_kegiatan" id="periksa-berkas" wire:model='jenis_kegiatan'>
                        <label for="periksa-berkas" class="flex items-center gap-2 text-nest-700 font-bold">Pemeriksaan Berkas</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::SURVEY_LAPANGAN }}" name="jenis_kegiatan" id="survey-lapangan" wire:model='jenis_kegiatan'>
                        <label for="survey-lapangan" class="flex items-center gap-2 text-nest-700 font-bold">Survey Lapangan</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="radio" class="h-4 w-4 accent-nest-700" value="{{ App\utils\enums\BackOfficeStatusEnum::RAPAT_TIM }}" name="jenis_kegiatan" id="rapat-tim" wire:model='jenis_kegiatan'>
                        <label for="rapat-tim" class="flex items-center gap-2 text-nest-700 font-bold">Rapat Tim Teknis</label>
                    </div>
                </div>
                @error('jenis_kegiatan')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-ink-100 text-ink-800 hover:bg-ink-200" type="button" wire:click='cancel'>{{ $saved ? 'Kembali' : 'Batal' }}</button>
                    <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>