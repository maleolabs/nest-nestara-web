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
                <div class="form-check">
                    <input type="checkbox" {{ $properti_entri_mandatori ? 'checked' : '' }} class="form-check-input" wire:model.live='properti_entri_mandatori.{{ $data_entri->id }}'>
                </div>
            </div>
        </div>
    @empty
        <div class="border rounded-3 p-4 w-50">
            <p class="mb-2 text-sm fw-medium">Tidak ada data entri</p>
            <button class="btn btn-sm bg-gradient-light text-capitalize" type="button">buat baru</button>
        </div>
    @endforelse
    @if (count($properti_data_entri) > 0)
        <span class="text-xs">
            <i class="fa-solid fa-circle-info me-1"></i>
            untuk menyimpan tekan enter
        </span>
    @endif
</div>