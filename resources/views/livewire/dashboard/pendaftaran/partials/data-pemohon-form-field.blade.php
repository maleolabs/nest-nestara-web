<div class="border rounded-2 mb-3 p-3">
    <div class="text-bold text-sm mb-2">Sumber Identitas</div>
    <div class="d-flex gap-3 align-items-center">
        <div class="form-check ps-0">
            <input type="radio" wire:model='form_data_pemohon.sumber_identitas' class="form-check-input" name="sumber_id" value="ktp" id="ktp">
            <label for="ktp" class="custom-control-label">KTP</label>
        </div>
        <div class="form-check">
            <input type="radio" wire:model='form_data_pemohon.sumber_identitas' class="form-check-input" name="sumber_id" value="sim" id="sim">
            <label for="sim" class="custom-control-label">sim</label>
        </div>
        <div class="form-check">
            <input type="radio" wire:model='form_data_pemohon.sumber_identitas' class="form-check-input" name="sumber_id" value="passport" id="passport">
            <label for="passport" class="custom-control-label">passport</label>
        </div>
    </div>
    @error('form_data_pemohon.sumber_identitas')
        <div class="text-sm text-danger">{{ $message }}</div>
    @enderror
    <div class="input-group input-group-outline mt-2">
        <label for="nomor_id" class="form-label">ID</label>
        <input type="text" wire:model='form_data_pemohon.nomor_id' class="form-control @error('form_data_pemohon.nomor_id') is-invalid @enderror" name="nomor_id" id="nomor_id">
    </div>
    @error('form_data_pemohon.nomor_id')
        <div class="text-sm text-danger mt-1">{{ $message }}</div>
    @enderror
</div>
<div class="border rounded-2 mb-3 p-3">
    <div class="text-bold text-sm mb-2">Informasi Kontak</div>
    <div class="input-group input-group-outline">
        <label for="nama_pemohon" class="form-label">Nama Pemohon</label>
        <input type="text" wire:model='form_data_pemohon.nama_pemohon' class="form-control @error('form_data_pemohon.nama_pemohon') is-invalid @enderror" name="nama_pemohon" id="nama_pemohon">
    </div>
    @error('form_data_pemohon.nama_pemohon')
        <div class="text-sm text-danger mt-1">{{ $message }}</div>
    @enderror
    <div class="input-group input-group-outline mt-3">
        <label for="no_telepon" class="form-label">Nomor Telepon</label>
        <input type="text" inputmode="tel" wire:model='form_data_pemohon.no_telepon' class="form-control @error('form_data_pemohon.no_telepon') is-invalid @enderror" name="no_telepon" id="no_telepon">
    </div>
    @error('form_data_pemohon.no_telepon')
        <div class="text-sm text-danger mt-1">{{ $message }}</div>
    @enderror
</div>
<div class="border rounded-2 mb-3 p-3">
    <div class="text-bold text-sm mb-2">Alamat</div>
    <label for="provinsi" class="mb-0">Provinsi</label>
    <div class="input-group input-group-static">
        <select class="form-select px-3 @error('form_data_pemohon.provinsi') is-invalid @enderror" name="provinsi" id="provinsi" wire:model.live='form_data_pemohon.provinsi' wire:loading.class='disabled' wire:target='form_data_pemohon.provinsi, form_data_pemohon.kabupaten, form_data_pemohon.kecamatan, form_data_pemohon.kelurahan'>
            @if (!is_value_exists($form_data_pemohon->provinsi))
                <option selected>Pilih provinsi</option>
            @endif
            @foreach ($allProvinsi as $prov)
                <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
            @endforeach
        </select>
    </div>
    @error('form_data_pemohon.provinsi')
        <div class="text-sm text-danger mt-1">{{ $message }}</div>
    @enderror
    <label for="kabupaten" class="mb-0 mt-3">Kabupaten</label>
    <div class="input-group input-group-static">
        <select class="form-select px-3 @error('form_data_pemohon.kabupaten') is-invalid @enderror" name="kabupaten" id="kabupaten" wire:model.live='form_data_pemohon.kabupaten' wire:loading.class='disabled' wire:target='form_data_pemohon.provinsi, form_data_pemohon.kabupaten, form_data_pemohon.kecamatan, form_data_pemohon.kelurahan'>
            @if (!is_value_exists($form_data_pemohon->kabupaten))
                <option selected>Pilih kabupaten/kota</option>
            @endif
            @foreach ($allKabupaten as $kab)
                <option value="{{ $kab['id'] }}">{{ $kab['name'] }}</option>
            @endforeach
        </select>
    </div>
    @error('form_data_pemohon.kabupaten')
        <div class="text-sm text-danger mt-1">{{ $message }}</div>
    @enderror
    @if (is_value_exists($form_data_pemohon->kabupaten))
        <label for="kecamatan" class="mb-0 mt-3">kecamatan</label>
        <div class="input-group input-group-static">
            <select class="form-select px-3 @error('form_data_pemohon.kecamatan') is-invalid @enderror" name="kecamatan" id="kecamatan" wire:model.live='form_data_pemohon.kecamatan' wire:loading.class='disabled' wire:target='form_data_pemohon.provinsi, form_data_pemohon.kabupaten, form_data_pemohon.kecamatan, form_data_pemohon.kelurahan'>
                @if (!is_value_exists($form_data_pemohon->kecamatan))
                    <option selected>Pilih kecamatan</option>
                @endif
                @foreach ($allKecamatan as $kec)
                    <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
                @endforeach
            </select>
        </div>
        @error('form_data_pemohon.kecamatan')
            <div class="text-sm text-danger mt-1">{{ $message }}</div>
        @enderror
    @endif
    @if (is_value_exists($form_data_pemohon->kecamatan))
        <label for="kelurahan" class="mb-0 mt-3">kelurahan</label>
        <div class="input-group input-group-static">
            <select class="form-select px-3 @error('form_data_pemohon.kelurahan') is-invalid @enderror" name="kelurahan" id="kelurahan" wire:model='form_data_pemohon.kelurahan' wire:loading.class='disabled' wire:target='form_data_pemohon.provinsi, form_data_pemohon.kabupaten, form_data_pemohon.kecamatan, form_data_pemohon.kelurahan'>
                @if (!is_value_exists($form_data_pemohon->kelurahan))
                    <option selected>Pilih kelurahan/desa</option>
                @endif
                @foreach ($allKelurahan as $kel)
                    <option value="{{ $kel['id'] }}">{{ $kel['name'] }}</option>
                @endforeach
            </select>
        </div>
        @error('form_data_pemohon.kelurahan')
            <div class="text-sm text-danger mt-1">{{ $message }}</div>
        @enderror
    @endif
    @if (!is_value_exists($form_data_pemohon->kabupaten) || !is_value_exists($form_data_pemohon->kecamatan))
        <div wire:loading wire:target='form_data_pemohon.kabupaten, form_data_pemohon.kecamatan'>
            <div class="text-sm text-muted mt-3">
                <i class="fa-solid fa-circle-info me-1"></i>
                Mohon tunggu, sedang mengambil data..
            </div>
        </div>
    @endif
    <label for="detail_alamat" class="mt-3 d-block">Detail Alamat</label>
    <div class="input-group input-group-dynamic">
        <textarea name="detail_alamat" wire:model='form_data_pemohon.detail_alamat' id="detail_alamat" rows="2" class="form-control" placeholder="jln. mangga, no 27"></textarea>
    </div>
    @error('form_data_pemohon.detail_alamat')
        <div class="text-sm text-danger mt-1">{{ $message }}</div>
    @enderror
    <label for="alamat_luar_negeri" class="mb-0 mt-3">Alamat di luar negeri (isikan jika ada)</label>
    <div class="input-group input-group-dynamic">
        <textarea name="alamat_luar_negeri" wire:model='form_data_pemohon.alamat_luar_negeri' id="alamat_luar_negeri" rows="2" class="form-control" placeholder="manggo str, no 27"></textarea>
    </div>
</div>