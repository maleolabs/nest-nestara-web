<div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
    <div class="border-b border-ink-200 px-4 py-3 border-b flex items-center gap-2">
        <img src="{{ asset('storage/users/' . ($pemohon->user ? $pemohon->user->foto : 'profile.png')) }}" alt="{{ $pemohon->nama }}" class="img-circle img-circle-sm bg-ink-100 text-ink-800">
        <h6 class="font-semibold mb-0">{{ str_capitalize($pemohon->nama_pemohon, true) }}</h6>
    </div>
    <div class="p-4 min-h-[50vh]">
        <div class="grid grid-cols-1 gap-4 items-end mb-3">
            <div class="min-w-0">
                <label for="sumber_identitas" class="mb-1 block text-sm font-semibold text-ink-700">sumber identitas</label>
                <div class="space-y-1">
                    <select type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 px-2" id="sumber_identitas" wire:model='form_data_pemohon.sumber_identitas'>
                        <option class="uppercase" value="ktp">ktp</option>
                        <option class="uppercase" value="sim">sim</option>
                        <option class="uppercase" value="passport">passport</option>
                    </select>
                    @error('form_data_pemohon.sumber_identitas')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="min-w-0">
                <div class="space-y-1">
                    <label for="id" class="mb-1 block text-sm font-semibold text-ink-700">ID</label>
                    <input type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="id" wire:model='form_data_pemohon.nomor_id'>
                    @error('form_data_pemohon.nomor_id')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="min-w-0 mb-3">
                <div class="space-y-1">
                    <label for="nama_pemohon" class="mb-1 block text-sm font-semibold text-ink-700 capitalize">nama pemohon</label>
                    <input type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="nama_pemohon" wire:model='form_data_pemohon.nama_pemohon'>
                    @error('form_data_pemohon.nama_pemohon')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="min-w-0 mb-3">
                <div class="space-y-1">
                    <label for="no_telepon" class="mb-1 block text-sm font-semibold text-ink-700 capitalize">nomor telepon</label>
                    <input type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="no_telepon" wire:model='form_data_pemohon.no_telepon'>
                    @error('form_data_pemohon.no_telepon')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 items-end mb-3">
            <div class="min-w-0">
                <label for="provinsi" class="mb-1 block text-sm font-semibold text-ink-700 capitalize">provinsi</label>
                <div class="space-y-1">
                    <select type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 px-2" id="provinsi" wire:model='form_data_pemohon.provinsi'>
                        <option selected>Pilih provinsi</option>
                        @foreach ($allProvinsi as $prov)
                            <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.provinsi')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="min-w-0">
                <label for="kabupaten" class="mb-1 block text-sm font-semibold text-ink-700 capitalize">kabupaten</label>
                <div class="space-y-1">
                    <select type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 px-2" id="kabupaten" wire:model='form_data_pemohon.kabupaten'>
                        <option selected>Pilih kabupaten</option>
                        @foreach ($allKabupaten as $kab)
                            <option value="{{ $kab['id'] }}">{{ $kab['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.kabupaten')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 items-end mb-3">
            <div class="min-w-0">
                <label for="kecamatan" class="mb-1 block text-sm font-semibold text-ink-700 capitalize">kecamatan</label>
                <div class="space-y-1">
                    <select type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 px-2" id="kecamatan" wire:model='form_data_pemohon.kecamatan'>
                        <option selected>Pilih kecamatan</option>
                        @foreach ($allKecamatan as $kec)
                            <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.kecamatan')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="min-w-0">
                <label for="kecamatan" class="mb-1 block text-sm font-semibold text-ink-700 capitalize">kelurahan</label>
                <div class="space-y-1">
                    <select type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 px-2" id="kelurahan" wire:model='form_data_pemohon.kelurahan'>
                        <option selected>Pilih kelurahan</option>
                        @foreach ($allKelurahan as $kel)
                            <option value="{{ $kel['id'] }}">{{ $kel['name'] }}</option>
                        @endforeach
                    </select>
                    @error('form_data_pemohon.kelurahan')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="min-w-0">
                <div class="space-y-1">
                    <label for="detail_alamat" class="mb-1 block text-sm font-semibold text-ink-700">Detail Alamat</label>
                    <textarea name="detail_alamat" id="detail_alamat" rows="3" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" wire:model='form_data_pemohon.detail_alamat'></textarea>
                    @error('form_data_pemohon.detail_alamat')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="min-w-0">
                <div class="space-y-1">
                    <label for="alamat_luar_negeri" class="mb-1 block text-sm font-semibold text-ink-700">Alamat Luar Negeri</label>
                    <textarea name="alamat_luar_negeri" id="alamat_luar_negeri" rows="3" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" wire:model='form_data_pemohon.alamat_luar_negeri'></textarea>
                    @error('form_data_pemohon.alamat_luar_negeri')
                        <div class="text-red-600 text-sm">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mt-4">
            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white" type="button" wire:click='saveDataPemohon' wire:loading.class='disabled' wire:target='saveDataPemohon'>
                <span wire:loading.remove wire:target='saveDataPemohon'>simpan</span>
                <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-ink-300 border-t-ink-800" wire:loading wire:target='saveDataPemohon' aria-label="Memuat"></span>
            </button>
        </div>
    </div>
</div>
