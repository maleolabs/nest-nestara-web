<div class="card">
    <div class="card-header border-bottom d-flex align-items-center gap-2">
        <img src="{{ asset('storage/users/' . ($pemohon->user ? $pemohon->user->foto : 'profile.png')) }}" alt="{{ $pemohon->nama }}" class="img-circle img-circle-sm bg-gradient-light">
        <h6 class="fw-semibold mb-0">{{ str_capitalize($pemohon->nama_pemohon, true) }}</h6>
    </div>
    <div class="card-body min-vh-50">
        <div class="row align-items-end mb-3">
            <div class="col-sm-6">
                <label for="sumber_identitas" class="form-label">sumber identitas</label>
                <div class="input-group">
                    <select type="text" class="form-select px-2" id="sumber_identitas" wire:model='form_data_pemohon.sumber_identitas'>
                        <option class="text-uppercase" value="ktp">ktp</option>
                        <option class="text-uppercase" value="sim">sim</option>
                        <option class="text-uppercase" value="passport">passport</option>
                    </select>
                    @error('form_data_pemohon.sumber_identitas')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <div class="input-group input-group-outline">
                    <label for="id" class="form-label">ID</label>
                    <input type="text" class="form-control" id="id" wire:model='form_data_pemohon.nomor_id'>
                    @error('form_data_pemohon.nomor_id')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 mb-3">
                <div class="input-group input-group-outline">
                    <label for="nama_pemohon" class="form-label text-capitalize">nama pemohon</label>
                    <input type="text" class="form-control" id="nama_pemohon" wire:model='form_data_pemohon.nama_pemohon'>
                    @error('form_data_pemohon.nama_pemohon')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6 mb-3">
                <div class="input-group input-group-outline">
                    <label for="nama_pemohon" class="form-label text-capitalize">nomor telepon</label>
                    <input type="text" class="form-control" id="nama_pemohon" wire:model='form_data_pemohon.no_telepon'>
                    @error('form_data_pemohon.no_telepon')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row align-items-end mb-3">
            <div class="col-sm-6">
                <label for="provinsi" class="form-label text-capitalize">provinsi</label>
                <div class="input-group input-group-outline">
                    <select type="text" class="form-select px-2" id="provinsi" wire:model='form_data_pemohon.provinsi'>
                        <option selected>Pilih provinsi</option>
                        @foreach ($allProvinsi as $prov)
                            <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.provinsi')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <label for="kabupaten" class="form-label text-capitalize">kabupaten</label>
                <div class="input-group input-group-outline">
                    <select type="text" class="form-select px-2" id="kabupaten" wire:model='form_data_pemohon.kabupaten'>
                        <option selected>Pilih kabupaten</option>
                        @foreach ($allKabupaten as $kab)
                            <option value="{{ $kab['id'] }}">{{ $kab['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.kabupaten')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row align-items-end mb-3">
            <div class="col-sm-6">
                <label for="kecamatan" class="form-label text-capitalize">kecamatan</label>
                <div class="input-group input-group-outline">
                    <select type="text" class="form-select px-2" id="kecamatan" wire:model='form_data_pemohon.kecamatan'>
                        <option selected>Pilih kecamatan</option>
                        @foreach ($allKecamatan as $kec)
                            <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.kecamatan')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-sm-6">
                <label for="kecamatan" class="form-label text-capitalize">kelurahan</label>
                <div class="input-group input-group-outline">
                    <select type="text" class="form-select px-2" id="kelurahan" wire:model='form_data_pemohon.kelurahan'>
                        <option selected>Pilih kelurahan</option>
                        @foreach ($allKelurahan as $kel)
                            <option value="{{ $kel['id'] }}">{{ $kel['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.kelurahan')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="input-group input-group-outline">
                    <label for="detail_alamat" class="form-label">Detail Alamat</label>
                    <textarea name="detail_alamat" id="detail_alamat" rows="3" class="form-control" wire:model='form_data_pemohon.detail_alamat'></textarea>
                    @error('form_data_pemohon.detail_alamat')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="input-group input-group-outline">
                    <label for="alamat_luar_negeri" class="form-label">Alamat Luar Negeri</label>
                    <textarea name="alamat_luar_negeri" id="alamat_luar_negeri" rows="3" class="form-control" wire:model='form_data_pemohon.alamat_luar_negeri'></textarea>
                    @error('form_data_pemohon.alamat_luar_negeri')
                        <div class="text-danger text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mt-4">
            <button class="btn bg-gradient-primary" type="button" wire:click='saveDataPemohon' wire:loading.class='disabled' wire:target='saveDataPemohon'>
                <span wire:loading.remove wire:target='saveDataPemohon'>simpan</span>
                <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveDataPemohon'></span>
            </button>
        </div>
    </div>
</div>