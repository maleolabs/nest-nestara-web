<div>
    <md-linear-progress wire:loading wire:target='save, cancel' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="card">
        <div class="card-header border-bottom">
            <h6 class="text-bold mb-0">
                <i class="fa-solid fa-medal me-1"></i>
                Rekomendasi Permohonan Izin
            </h6>
        </div>
        <div class="card-body">
            <p class="mb-0 text-sm">Nama Izin</p>
            <h3 class="spacing-md">{{ str_capitalize($preview_izin->jenis_perizinan->nama, true) }}</h3>
            <p class="mb-0 text-sm">Nama Pemohon</p>
            <h6 class="text-bold">{{ str_capitalize($preview_izin->pemohon->nama_pemohon) }}</h6>
            <p class="mb-0 text-">Lokasi Izin</p>
            <h6 class="text-bold">{{ str_capitalize($preview_izin->lokasi_izin) }}</h6>
            <hr>
            <div class="rounded-4 bg-app-primary-light p-3 mb-3 text-sm text-primary">
                @if ($saved)
                    <i class="fa-solid fa-circle-check me-1"></i>
                    Rekomendasi permohonan berhasil disimpan!
                @else
                    Pada tahapan rekomendasi permohonan izin, pegawai harus menentukan status rekomendasi (direkomendasikan, tidak direkomendasikan, rekomendasi dengan catatan, atau perlu pembahasan lebih lanjut) dan mencatat jenis kegiatan yang dilakukan (pemeriksaan berkas, survei lapangan, atau rapat tim teknis).
                @endif
            </div>
            <form wire:submit.prevent='save'>
                <p class="text-sm mb-0">Rekomendasi</p>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check ps-0">
                        <input type="radio" class="form-check-input" value="{{ App\utils\enums\BackOfficeStatusEnum::DIREKOM }}" name="rekomendasi" id="direkom" wire:model='rekomendasi'>
                        <label for="direkom" class="form-check-label app-text-primary text-bold">Direkomendasikan</label>
                    </div>
                    <div class="form-check ps-0">
                        <input type="radio" class="form-check-input" value="{{ App\utils\enums\BackOfficeStatusEnum::TIDAK_DIREKOM }}" name="rekomendasi" id="tidak-direkom" wire:model='rekomendasi'>
                        <label for="tidak-direkom" class="form-check-label app-text-primary text-bold">TIdak Direkomendasikan</label>
                    </div>
                    <div class="form-check ps-0">
                        <input type="radio" class="form-check-input" value="{{ App\utils\enums\BackOfficeStatusEnum::REKOM_CATATAN }}" name="rekomendasi" id="rekom-bersyarat" wire:model='rekomendasi'>
                        <label for="rekom-bersyarat" class="form-check-label app-text-primary text-bold">Direkomendasikan Dengan Catatan</label>
                    </div>
                    <div class="form-check ps-0">
                        <input type="radio" class="form-check-input" value="{{ App\utils\enums\BackOfficeStatusEnum::BAHAS_LANJUT }}" name="rekomendasi" id="bahas-lanjut" wire:model='rekomendasi'>
                        <label for="bahas-lanjut" class="form-check-label app-text-primary text-bold">Perlu Pembahasan Lebih Lanjut</label>
                    </div>
                </div>
                @error('rekomendasi')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
                <p class="text-sm mb-0 mt-3">Jenis Kegiatan</p>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check ps-0">
                        <input type="radio" class="form-check-input" value="{{ App\utils\enums\BackOfficeStatusEnum::PERIKSA_BERKAS }}" name="jenis_kegiatan" id="periksa-berkas" wire:model='jenis_kegiatan'>
                        <label for="periksa-berkas" class="form-check-label app-text-primary text-bold">Pemeriksaan Berkas</label>
                    </div>
                    <div class="form-check ps-0">
                        <input type="radio" class="form-check-input" value="{{ App\utils\enums\BackOfficeStatusEnum::SURVEY_LAPANGAN }}" name="jenis_kegiatan" id="survey-lapangan" wire:model='jenis_kegiatan'>
                        <label for="survey-lapangan" class="form-check-label app-text-primary text-bold">Survey Lapangan</label>
                    </div>
                    <div class="form-check ps-0">
                        <input type="radio" class="form-check-input" value="{{ App\utils\enums\BackOfficeStatusEnum::RAPAT_TIM }}" name="jenis_kegiatan" id="rapat-tim" wire:model='jenis_kegiatan'>
                        <label for="rapat-tim" class="form-check-label app-text-primary text-bold">Rapat Tim Teknis</label>
                    </div>
                </div>
                @error('jenis_kegiatan')
                    <div class="text-sm text-danger">{{ $message }}</div>
                @enderror
                <div class="d-flex align-items-center mt-4 gap-3">
                    <button class="btn btn-secondary" type="button" wire:click='cancel'>{{ $saved ? 'Kembali' : 'Batal' }}</button>
                    <button class="btn bg-gradient-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>