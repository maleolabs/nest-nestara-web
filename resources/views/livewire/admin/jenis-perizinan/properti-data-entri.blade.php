<div>
    <p class="text-xs text-muted mb-3 pb-1 border-bottom">Properti Data Entri</p>
    @if (count($properti_data_entri) > 0)
        <div class="row align-items-bottom mb-3">
            <div class="col-6">
                <p class="mb-0 text-xs fw-semibold text-capitalize">nama</p>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <p class="mb-0 text-xs fw-semibold text-capitalize">tipe</p>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <p class="mb-0 text-xs fw-semibold text-capitalize">maksimal</p>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <p class="mb-0 text-xs fw-semibold text-capitalize">mandatori</p>
            </div>
        </div>
    @endif
    @forelse ($properti_data_entri as $data_entri)
        <div class="row align-items-center mb-3">
            <div class="col-6 d-flex align-items-center gap-2">
                <div class="input-group input-group-sm border rounded-3">
                    <input type="text" class="form-control border-0 rounded-3" wire:model.lazy='properti_form.{{ $data_entri->id }}'>
                </div>
                <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='properti_form.{{ $data_entri->id }}'></i>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <div class="input-group input-group-sm rounded-3 border w-fit">
                    <select wire:model.lazy='properti_entri_tipe.{{ $data_entri->id }}' id="tipe-entri-{{ $data_entri->id }}" class="form-select border-0 text-center px-1">
                        <option value="text">Text</option>
                        <option value="textarea">Textarea</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                    </select>
                </div>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <div class="input-group input-group-sm rounded-3 border w-50">
                    <input type="number" class="form-control border-0 rounded-3" wire:model.lazy='properti_entri_max.{{ $data_entri->id }}'>
                </div>
            </div>
            <div class="col-2 d-flex justify-content-center">
                <div class="form-check form-switch">
                    <input type="checkbox" {{ $properti_entri_mandatori ? 'checked' : '' }} class="form-check-input" wire:model.live='properti_entri_mandatori.{{ $data_entri->id }}'>
                </div>
            </div>
        </div>
    @empty
        @if (!$new_form_active)
            <div class="border rounded-3 p-4 bg-gradient-light d-flex align-items-center justify-content-center flex-column">
                <p class="mb-2 text-sm fw-medium text-center">Tidak ada data entri</p>
                <button class="btn btn-sm bg-gradient-primary text-capitalize" type="button" wire:click='addNewDataProperti'>
                    <span wire:loading.remove wire:target='addNewDataProperti'>
                        tambah baru
                    </span>
                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='addNewDataProperti'></span>
                </button>
            </div>
        @endif
    @endforelse

    @if (count($properti_data_entri) > 0 && !$new_form_active)
        <p class="text-xs">
            <i class="fa-solid fa-circle-info me-1"></i>
            untuk menyimpan tekan enter
        </p>
        <button class="btn btn-sm bg-gradient-primary text-capitalize" type="button" wire:click='addNewDataProperti'>
            <span wire:loading.remove wire:target='addNewDataProperti'>
                tambah baru
            </span>
            <span class="spinner-border spinner-border-sm" wire:loading wire:target='addNewDataProperti'></span>
        </button>
    @endif

    @if ($new_form_active)
        <div class="border rounded-3 p-3">
            <h6 class="fw-semibold text-capitalize">formulir menambah properti data entri</h6>
            <form wire:submit.prevent='saveNewDataProperti'>
                <div class="row mb-3">
                    <div class="col-6 mb-1">
                        <p class="text-xs text-capitalize mb-0 fw-semibold">nama</p>
                    </div>
                    <div class="col-2 mb-1">
                        <p class="text-xs text-capitalize mb-0 fw-semibold text-center">tipe</p>
                    </div>
                    <div class="col-2 mb-1">
                        <p class="text-xs text-capitalize mb-0 fw-semibold text-center">maksimal</p>
                    </div>
                    <div class="col-2 mb-1">
                        <p class="text-xs text-capitalize mb-0 fw-semibold text-center">mandatori</p>
                    </div>
                    <div class="col-6">
                        <div class="input-group input-group-sm border rounded-3">
                            <input type="text" class="form-control border-0 rounded-3" wire:model='properti_data_form.nama' placeholder="masukkan nama properti data entri">
                        </div>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                        <div class="input-group input-group-sm rounded-3 border w-fit">
                            <select wire:model='properti_data_form.tipe' id="tipe-entri" class="form-select border-0 text-center px-1">
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                        <div class="input-group input-group-sm rounded-3 border w-50">
                            <input type="number" class="form-control border-0 rounded-3" wire:model='properti_data_form.max'>
                        </div>
                    </div>
                    <div class="col-2 d-flex justify-content-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" wire:model.live='properti_data_form.mandatori'>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm bg-gradient-light text-capitalize" type="button" wire:click='addNewDataProperti'>
                        <span wire:loading.remove wire:target='addNewDataProperti'>batal</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='addNewDataProperti'></span>
                    </button>
                    <button class="btn btn-sm bg-gradient-primary text-capitalize" type="submit">
                        <span wire:loading.remove wire:target='saveNewDataProperti'>simpan</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveNewDataProperti'></span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>