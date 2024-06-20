<h5 class="text-bold mb-0">
    <i class="fa-solid fa-file-circle-plus me-1"></i>
    Formulir Pengisian Data Izin Baru
</h5>
<p class="text-muted text-sm">mohon untuk mengisi semua data berikut!</p>
<form wire:submit.prevent='save'>
    @csrf
    <div class="row">
        <div class="col-sm-8 mb-sm-0 mb-3">
            <div class="border rounded-2 mb-3 p-3">
                <h6 class="text-bold text-sm mb-3">Data Permohonan</h6>
                <label for="id" class="form-label">Jenis Izin</label>
                <div class="input-group input-group-outline">
                    <select class="form-select" id="jenisPerizinanSelect" wire:model='data_izin_form.jenis_izin'>
                        @foreach ($allPerizinan as $perizinan)
                            <option value="{{ $perizinan->jenis }}">{{ $perizinan->jenis }}</option>
                        @endforeach
                    </select>
                </div>
                @error('data_izin_form.jenis_izin')
                    <div class="text-sm text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <h6>Pemohon Izin</h6>
             <ul class="nav nav-tabs nav-tabs-bordered">
                <li class="nav-item">
                  <button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#existing-data">Ambil data pemohon izin</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#new-data">Buat data pemohon izin baru</button>
                </li>
              </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="existing-data">
                    <div class="border-bottom border-start border-end rounded p-3">
                        <div class="input-group input-group-outline">
                            <label for="cari_pemohon" class="form-label">Cari nama atau id pemohon</label>
                            <input type="text" class="form-control" id="cari_pemohon" wire:model.live='cari_pemohon'>
                            <div class="position-absolute end-2 top-50 translate-middle" wire:loading wire:target='cari_pemohon'>
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="new-data">
                    @include('livewire.dashboard.pendaftaran.partials.data-pemohon-form-field')
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <h6 class="text-md">Info</h6>
            <p class="text-xs text-secondary">
                Pastikan semua data yang Anda masukkan sudah benar sebelum menekan tombol "Simpan". Setelah menekan tombol "Simpan", data akan diproses dan disimpan dalam sistem. Anda masih dapat mengedit informasi yang sudah disimpan dengan mengakses halaman edit jika diperlukan. 
            </p>
            <hr class="mb-2 mt-0">
            <button class="btn bg-gradient-primary w-100 mt-3" type="submit">simpan</button>
            <button class="btn btn-outline-secondary w-100 mt-3" type="reset" wire:click='resetData'>reset</button>
        </div>
    </div>
</form>