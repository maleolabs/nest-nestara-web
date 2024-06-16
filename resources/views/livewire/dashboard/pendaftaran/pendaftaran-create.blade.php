<div class="content-wrap border-top border-1 p-4">
    <h5 class="text-bold mb-0">Formulir Pengisian Data Pemohon Baru</h5>
    <p class="text-muted text-sm">mohon untuk mengisi semua data berikut!</p>
    <form action="javascript:;">
        @csrf
        <div class="row">
            <div class="col-sm-8 mb-sm-0 mb-3">
                <div class="border rounded-2 mb-3 p-3">
                    <div class="text-bold text-sm mb-2">Sumber Identitas</div>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="form-check ps-0">
                            <input type="radio" class="form-check-input" name="sumber_id" id="ktp">
                            <label for="ktp" class="custom-control-label">KTP</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="sumber_id" id="sim">
                            <label for="sim" class="custom-control-label">sim</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="sumber_id" id="passport">
                            <label for="passport" class="custom-control-label">passport</label>
                        </div>
                    </div>
                    <div class="input-group input-group-outline">
                        <label for="id" class="form-label">ID</label>
                        <input type="number" class="form-control @error('id') is-invalid @elseif(old('id')) is-valid @enderror" name="id" id="id" value="{{ old('id') }}">
                    </div>
                </div>
                <div class="border rounded-2 mb-3 p-3">
                    <div class="text-bold text-sm mb-2">Informasi Kontak</div>
                    <div class="input-group input-group-outline mb-3">
                        <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
                        <input type="text" class="form-control @error('nama_pemohon') is_invalid @elseif(old('nama_pemohon')) is-valid @enderror" name="nama_pemohon" id="nama_pemohon" value="{{ old('nama_pemohon') }}">
                    </div>
                    <div class="input-group input-group-outline">
                        <label for="no_telepon" class="form-label">Nomor Telepon</label>
                        <input type="text" inputmode="tel" class="form-control @error('no_telepon') is_invalid @elseif(old('no_telepon')) is-valid @enderror" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}">
                    </div>
                </div>
                <div class="border rounded-2 mb-3 p-3">
                    <div class="text-bold text-sm mb-2">Alamat</div>
                    <label for="provinsi" class="mb-0">Provinsi</label>
                    <div class="input-group input-group-static mb-3">
                        <select class="form-select px-3 @error('provinsi') is_invalid @elseif(old('provinsi')) is-valid @enderror" name="provinsi" id="provinsi" wire:model='provinsi' value="{{ old('provinsi') }}">
                            @foreach ($allProvinsi as $prov)
                                <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="kabupaten" class="mb-0">Kabupaten</label>
                    <div class="input-group input-group-static mb-3">
                        <select class="form-select px-3 @error('kabupaten') is_invalid @elseif(old('kabupaten')) is-valid @enderror" name="kabupaten" id="kabupaten" wire:model='kabupaten' value="{{ old('kabupaten') }}">
                            @foreach ($allKabupaten as $kab)
                                <option value="{{ $kab['id'] }}">{{ $kab['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="kecamatan" class="mb-0">kecamatan</label>
                    <div class="input-group input-group-static mb-3">
                        <select class="form-select px-3 @error('kecamatan') is_invalid @elseif(old('kecamatan')) is-valid @enderror" name="kecamatan" id="kecamatan" wire:model='kecamatan' value="{{ old('kecamatan') }}">
                            @foreach ($allKecamatan as $kec)
                                <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="kelurahan" class="mb-0">kelurahan</label>
                    <div class="input-group input-group-static mb-3">
                        <select class="form-select px-3 @error('kelurahan') is_invalid @elseif(old('kelurahan')) is-valid @enderror" name="kelurahan" id="kelurahan" wire:model='kelurahan' value="{{ old('kelurahan') }}">
                            @foreach ($allKelurahan as $kel)
                                <option value="{{ $kel['id'] }}">{{ $kel['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="detail_alamat" class="mb-0">Detail Alamat</label>
                    <div class="input-group input-group-dynamic mb-3">
                        <textarea name="detail_alamat" id="detail_alamat" rows="2" class="form-control" placeholder="jln. mangga, no 27"></textarea>
                    </div>
                    <label for="alamat_luar_negeri" class="mb-0">Alamat di luar negeri (isikan jika ada)</label>
                    <div class="input-group input-group-dynamic mb-3">
                        <textarea name="alamat_luar_negeri" id="alamat_luar_negeri" rows="2" class="form-control" placeholder="jln. mangga, no 27"></textarea>
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