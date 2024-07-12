<h6 class="text-bold mb-4">
    <i class="fa-regular fa-circle-right me-1"></i>
    Entri Tinjauan Lapangan
</h6>
@if ($this->hasTinjauanLapangan())
    <p class="mb-1 text-muted text-sm">Nomor Surat</p>
    <h6 class="mb-3">{{ $this->getCleanNoSurat() . $this->no_surat_format }}</h6>
    <p class="mb-1 text-muted text-sm">Penanda Tangan</p>
    <h6 class="mb-3"></h6>
    <p class="mb-1 text-muted text-sm">Tim Teknis</p>
    <h6 class="mb-3"></h6>
@else
    <form wire:submit.prevent="saveTinjauanLapangan">
        <div class="row">
            <div class="col-md-6">
                <div class="app-input-group mb-1">
                    <input type="number" class="form-control @error('no_surat')is-invalid @enderror" id="no_surat" wire:model.live="no_surat">
                    <span class="input-group-text">{{ $no_surat_format }}</span>
                </div>
                <div class="mt-1 text-sm @error('no_surat')mb-1 @else mb-3 @enderror">{{ "Nomor surat akan seperti ini: " . $this->getCleanNoSurat() . "{$no_surat_format}" }}</div>
                @error('no_surat')
                    <div class="text-danger text-sm mb-3">{{ $message }}</div>
                @enderror
                <div class="input-group @error('penandatangan')mb-1 @else mb-3 @enderror">
                    <select class="form-select px-2 @error('penandatangan')is-invalid @enderror" id="penandatangan" wire:model='penandatangan'>
                        <option selected>Pilih Penandatangan</option>
                        @foreach ($semua_penanda_tangan as $penanda_tangan)
                            <option value="{{ $penanda_tangan->id }}">{{ "{$penanda_tangan->nama} -> {$penanda_tangan->jabatan}" }}</option>
                        @endforeach
                    </select>
                </div>
                @error('penandatangan')
                    <div class="text-danger text-sm mb-3">{{ $message }}</div>
                @enderror
                <p class="mb-1 text-sm">Tim Teknis</p>
                @foreach ($semua_tim_teknis as $timteknis)
                    <div class="form-check ps-0">
                        <input type="checkbox" class="form-check-input" id="tim_teknis-{{ $timteknis->id }}" name="tim_teknis" wire:model="tim_teknis.{{ $timteknis->id }}">
                        <label for="tim_teknis-{{ $timteknis->id }}" class="form-check-label">{{ $timteknis->nama }}</label>
                    </div>
                @endforeach
                @error('tim_teknis')
                    <div class="text-danger text-sm">{{ $message }}</div>
                @enderror
                <div class="mt-3">
                    <button type="submit" class="btn bg-gradient-primary">
                        simpan
                    </button>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </form>
@endif