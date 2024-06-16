<div class="content-wrap border-top border-1 p-4">
    <md-linear-progress wire:loading wire:target='save' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <h5 class="text-bold mb-0">Formulir Pengisian Data Pemohon Baru</h5>
    <p class="text-muted text-sm">mohon untuk mengisi semua data berikut!</p>
    <form wire:submit.prevent='save'>
        @csrf
        <div class="row">
            <div class="col-sm-8 mb-sm-0 mb-3">
                <div class="border rounded-2 mb-3 p-3">
                    <div class="text-bold text-sm mb-2">Sumber Identitas</div>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="form-check ps-0">
                            <input type="radio" wire:model='form.sumber_identitas' class="form-check-input" name="sumber_id" value="ktp" id="ktp">
                            <label for="ktp" class="custom-control-label">KTP</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" wire:model='form.sumber_identitas' class="form-check-input" name="sumber_id" value="sim" id="sim">
                            <label for="sim" class="custom-control-label">sim</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" wire:model='form.sumber_identitas' class="form-check-input" name="sumber_id" value="passport" id="passport">
                            <label for="passport" class="custom-control-label">passport</label>
                        </div>
                    </div>
                    @error('form.sumber_identitas')
                        <div class="text-sm text-danger">{{ $message }}</div>
                    @enderror
                    <div class="input-group input-group-outline mt-2">
                        <label for="id" class="form-label">ID</label>
                        <input type="text" wire:model='form.nomor_id' class="form-control @error('form.nomor_id') is-invalid @enderror" name="id" id="id">
                    </div>
                    @error('form.nomor_id')
                        <div class="text-sm text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="border rounded-2 mb-3 p-3">
                    <div class="text-bold text-sm mb-2">Informasi Kontak</div>
                    <div class="input-group input-group-outline">
                        <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                        <input type="text" wire:model='form.nama_pemohon' class="form-control @error('form.nama_pemohon') is-invalid @enderror" name="nama_pemohon" id="nama_pemohon">
                    </div>
                    @error('form.nama_pemohon')
                        <div class="text-sm text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <div class="input-group input-group-outline mt-3">
                        <label for="no_telepon" class="form-label">Nomor Telepon</label>
                        <input type="text" inputmode="tel" wire:model='form.no_telepon' class="form-control @error('form.no_telepon') is-invalid @enderror" name="no_telepon" id="no_telepon">
                    </div>
                    @error('form.no_telepon')
                        <div class="text-sm text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="border rounded-2 mb-3 p-3">
                    <div class="text-bold text-sm mb-2">Alamat</div>
                    <label for="provinsi" class="mb-0">Provinsi</label>
                    <div class="input-group input-group-static">
                        <select class="form-select px-3 @error('form.provinsi') is-invalid @enderror" name="provinsi" id="provinsi" wire:model.live='form.provinsi' wire:loading.class='disabled' wire:target='form.provinsi, form.kabupaten, form.kecamatan, form.kelurahan'>
                            @foreach ($allProvinsi as $prov)
                                <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('form.provinsi')
                        <div class="text-sm text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <label for="kabupaten" class="mb-0 mt-3">Kabupaten</label>
                    <div class="input-group input-group-static">
                        <select class="form-select px-3 @error('form.kabupaten') is-invalid @enderror" name="kabupaten" id="kabupaten" wire:model.live='form.kabupaten' wire:loading.class='disabled' wire:target='form.provinsi, form.kabupaten, form.kecamatan, form.kelurahan'>
                            <option selected>Pilih kabupaten/kota</option>
                            @foreach ($allKabupaten as $kab)
                                <option value="{{ $kab['id'] }}">{{ $kab['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('form.kabupaten')
                        <div class="text-sm text-danger mt-1">{{ $message }}</div>
                    @enderror
                    @if (is_value_exists($form->kabupaten))
                        <label for="kecamatan" class="mb-0 mt-3">kecamatan</label>
                        <div class="input-group input-group-static">
                            <select class="form-select px-3 @error('form.kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatan" wire:model.live='form.kecamatan' wire:loading.class='disabled' wire:target='form.provinsi, form.kabupaten, form.kecamatan, form.kelurahan'>
                                <option selected>Pilih kecamatan</option>
                                @foreach ($allKecamatan as $kec)
                                    <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.kecamatan')
                            <div class="text-sm text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                    @if (is_value_exists($form->kecamatan))
                        <label for="kelurahan" class="mb-0 mt-3">kelurahan</label>
                        <div class="input-group input-group-static">
                            <select class="form-select px-3 @error('form.kelurahan') is-invalid @enderror" name="kelurahan" id="kelurahan" wire:model='form.kelurahan' wire:loading.class='disabled' wire:target='form.provinsi, form.kabupaten, form.kecamatan, form.kelurahan'>
                                <option selected>Pilih kelurahan/desa</option>
                                @foreach ($allKelurahan as $kel)
                                    <option value="{{ $kel['id'] }}">{{ $kel['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('form.kelurahan')
                            <div class="text-sm text-danger mt-1">{{ $message }}</div>
                        @enderror
                    @endif
                    @if (!is_value_exists($form->kabupaten) || !is_value_exists($form->kecamatan))
                        <div wire:loading wire:target='form.kabupaten, form.kecamatan'>
                            <div class="text-sm text-muted mt-3">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Mohon tunggu, sedang mengambil data..
                            </div>
                        </div>
                    @endif
                    <label for="detail_alamat" class="mt-3 d-block">Detail Alamat</label>
                    <div class="input-group input-group-dynamic">
                        <textarea name="detail_alamat" wire:model='form.detail_alamat' id="detail_alamat" rows="2" class="form-control" placeholder="jln. mangga, no 27"></textarea>
                    </div>
                    @error('form.detail_alamat')
                        <div class="text-sm text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <label for="alamat_luar_negeri" class="mb-0 mt-3">Alamat di luar negeri (isikan jika ada)</label>
                    <div class="input-group input-group-dynamic">
                        <textarea name="alamat_luar_negeri" wire:model='form.alamat_luar_negeri' id="alamat_luar_negeri" rows="2" class="form-control" placeholder="manggo str, no 27"></textarea>
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
                <button class="btn btn-outline-secondary w-100 mt-3" type="reset">reset</button>
            </div>
        </div>
    </form>
</div>