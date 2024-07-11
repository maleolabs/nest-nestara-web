<div class="vh-30 position-relative">
    <div class="position-absolute z-1 inset-0 w-100 h-100 p-4 vh-30 overflow-hidden">
        <h5 class="text-bold mb-1">
            <i class="fa-regular fa-circle-right me-1"></i>
            Entri Data
        </h5>
        <div class="row">
            @foreach ($entri_data as $entri)
                @if ($entri->tipe == 'textarea')
                    <div class="col-12 mb-3">
                        <div class="input-group input-group-outline">
                            <label class="form-label">
                                {{ $entri->nama }}
                                @if ($entri->mandatori)
                                    <span class="text-danger ms-1">*</span>
                                @endif
                            </label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>
                @else
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-outline">
                            <label class="form-label">
                                {{ $entri->nama }}
                                @if ($entri->mandatori)
                                    <span class="text-danger ms-1">*</span>
                                @endif
                            </label>
                            <input type="{{ $entri->tipe }}" class="form-control">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="position-absolute inset-0 w-100 h-100 z-2 d-flex flex-column gap-2 align-items-center justify-content-center bg-gradient-light-up-bottom rounded-3">
        @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI)
            <p class="mb-0 text-bold">{{ $last_izin_status->keterangan }}</p>
            <div class="d-flex justify-content-center gap-3 align-items-center">
                <a href="{{ route('pegawai.pendataan') }}" class="btn bg-gradient-info d-flex align-items-center">
                    entri data kembali
                </a>
                <button class="btn bg-gradient-warning d-flex align-items-center" type="button" wire:click='keepOldEntri'>
                    tetap gunakan entri yang lama
                    <div class="spinner-border spinner-border-sm ms-2" role="status" wire:loading wire:target='keepOldEntri'>
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        @else
            <button class="btn bg-gradient-primary d-flex align-items-center" type="button" wire:click='entriData'>
                entri data
                <div class="spinner-border spinner-border-sm ms-2" role="status" wire:loading wire:target='entriData'>
                    <span class="visually-hidden">Loading...</span>
                </div>
            </button>
        @endif
    </div>
</div>