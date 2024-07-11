<h6 class="text-bold mb-4">
    <i class="fa-regular fa-circle-right me-1"></i>
    Entri Tinjauan Lapangan
</h6>
<form wire.submit.prevent="saveTinjaunLapangan">
    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-outline mb-3">
                <label for="no_surat" class="form-label">No Surat</label>
                <input type="text" class="form-control @error('no_surat')is-invalid @enderror" id="no_surat" wire:model="no_surat">
                @error('no_surat')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="input-group input-group-outline mb-3">
                <label for="penanda_tangan" class="form-label">No Surat</label>
                <select class="form-select @error('penanda_tangan')is-invalid @enderror" id="penanda_tangan" wire:model="penanda_tangan">
                    <option selected>Pilih Penandatangan</option>
                    <option value="jokow">jokowi</option>
                    <option value="prabowo">prabowo</option>
                </select>
                @error('penanda_tangan')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="input-group input-group-outline">
                <label for="tim_teknis" class="form-label">No Surat</label>
                <input type="text" class="form-control @error('tim_teknis')is-invalid @enderror" id="tim_teknis" wire:model="tim_teknis">
                @error('tim_teknis')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">

        </div>
    </div>
</form>