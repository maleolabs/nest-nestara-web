@php
    $inputBase = 'mt-1.5 w-full rounded-xl border bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-4 focus:ring-nest-100 transition';
    $inputOk = 'border-ink-200 focus:border-nest-600';
    $inputErr = 'border-red-300 focus:border-red-400 focus:ring-red-100';
@endphp

{{-- Sumber Identitas --}}
<section class="rounded-xl border border-ink-200 p-4">
    <h6 class="text-xs font-extrabold tracking-widest text-ink-500 uppercase">Sumber Identitas</h6>
    <div class="mt-3 flex flex-wrap gap-2">
        @foreach (['ktp' => 'KTP', 'sim' => 'SIM'] as $val => $label)
            <label for="src_{{ $val }}"
                class="cursor-pointer inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-bold transition
                    {{ $form_data_pemohon->sumber_identitas === $val ? 'bg-nest-700 text-white border-nest-700 shadow-sm' : 'bg-white text-ink-700 border-ink-200 hover:border-nest-300 hover:bg-nest-50' }}">
                <input type="radio" wire:model.live='form_data_pemohon.sumber_identitas' name="sumber_id" value="{{ $val }}" id="src_{{ $val }}" class="sr-only">
                <i class="fa-solid {{ $val === 'ktp' ? 'fa-id-card' : ($val === 'sim' ? 'fa-id-badge' : 'fa-plane') }} text-xs"></i> {{ $label }}
            </label>
        @endforeach
    </div>
    @error('form_data_pemohon.sumber_identitas')
        <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
    @enderror

    <div class="mt-4">
        <label for="nomor_id" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nomor ID</label>
        <input type="text" wire:model='form_data_pemohon.nomor_id' name="nomor_id" id="nomor_id" placeholder="Masukkan nomor identitas"
            class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.nomor_id') ? $inputErr : $inputOk }}">
        @error('form_data_pemohon.nomor_id')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
    </div>
</section>

{{-- Informasi Kontak --}}
<section class="rounded-xl border border-ink-200 p-4">
    <h6 class="text-xs font-extrabold tracking-widest text-ink-500 uppercase">Informasi Kontak</h6>
    <div class="mt-3 grid gap-4 sm:grid-cols-2">
        <div>
            <label for="nama_pemohon" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nama Pemohon</label>
            <input type="text" wire:model='form_data_pemohon.nama_pemohon' name="nama_pemohon" id="nama_pemohon" placeholder="Nama sesuai identitas"
                class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.nama_pemohon') ? $inputErr : $inputOk }}">
            @error('form_data_pemohon.nama_pemohon')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="no_telepon" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nomor Telepon</label>
            <input type="text" inputmode="tel" wire:model='form_data_pemohon.no_telepon' name="no_telepon" id="no_telepon" placeholder="08xx xxxx xxxx"
                class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.no_telepon') ? $inputErr : $inputOk }}">
            @error('form_data_pemohon.no_telepon')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</section>

{{-- Alamat --}}
<section class="rounded-xl border border-ink-200 p-4">
    <h6 class="text-xs font-extrabold tracking-widest text-ink-500 uppercase">Alamat</h6>
    <div class="mt-3 grid gap-4 sm:grid-cols-2">
        <div>
            <label for="provinsi" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Provinsi</label>
            <select name="provinsi" id="provinsi" wire:model.live='form_data_pemohon.provinsi'
                class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.provinsi') ? $inputErr : $inputOk }}">
                @if (!is_value_exists($form_data_pemohon->provinsi))
                    <option selected value="">Pilih provinsi</option>
                @endif
                @foreach ($allProvinsi as $prov)
                    <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                @endforeach
            </select>
            @error('form_data_pemohon.provinsi')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="kabupaten" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Kabupaten/Kota</label>
            <select name="kabupaten" id="kabupaten" wire:model.live='form_data_pemohon.kabupaten'
                class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.kabupaten') ? $inputErr : $inputOk }}">
                @if (!is_value_exists($form_data_pemohon->kabupaten))
                    <option selected value="">Pilih kabupaten/kota</option>
                @endif
                @foreach ($allKabupaten as $kab)
                    <option value="{{ $kab['id'] }}">{{ $kab['name'] }}</option>
                @endforeach
            </select>
            @error('form_data_pemohon.kabupaten')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
        </div>
        @if (is_value_exists($form_data_pemohon->kabupaten))
            <div>
                <label for="kecamatan" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" wire:model.live='form_data_pemohon.kecamatan'
                    class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.kecamatan') ? $inputErr : $inputOk }}">
                    @if (!is_value_exists($form_data_pemohon->kecamatan))
                        <option selected value="">Pilih kecamatan</option>
                    @endif
                    @foreach ($allKecamatan as $kec)
                        <option value="{{ $kec['id'] }}">{{ $kec['name'] }}</option>
                    @endforeach
                </select>
                @error('form_data_pemohon.kecamatan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>
        @endif
        @if (is_value_exists($form_data_pemohon->kecamatan))
            <div>
                <label for="kelurahan" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Kelurahan/Desa</label>
                <select name="kelurahan" id="kelurahan" wire:model='form_data_pemohon.kelurahan'
                    class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.kelurahan') ? $inputErr : $inputOk }}">
                    @if (!is_value_exists($form_data_pemohon->kelurahan))
                        <option selected value="">Pilih kelurahan/desa</option>
                    @endif
                    @foreach ($allKelurahan as $kel)
                        <option value="{{ $kel['id'] }}">{{ $kel['name'] }}</option>
                    @endforeach
                </select>
                @error('form_data_pemohon.kelurahan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>
        @endif
    </div>

    @if (!is_value_exists($form_data_pemohon->kabupaten) || !is_value_exists($form_data_pemohon->kecamatan))
        <div wire:loading wire:target='form_data_pemohon.kabupaten, form_data_pemohon.kecamatan'
            class="mt-3 flex items-center gap-2 rounded-lg bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 ring-1 ring-sky-200">
            <i class="fa-solid fa-spinner fa-spin"></i> Mengambil data wilayah…
        </div>
    @endif

    <div class="mt-4">
        <label for="detail_alamat" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Detail Alamat</label>
        <textarea name="detail_alamat" wire:model='form_data_pemohon.detail_alamat' id="detail_alamat" rows="2" placeholder="Jl. Mangga No. 27"
            class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.detail_alamat') ? $inputErr : $inputOk }}"></textarea>
        @error('form_data_pemohon.detail_alamat')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="mt-4">
        <label for="alamat_luar_negeri" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Alamat Luar Negeri <span class="normal-case font-medium text-ink-400">(opsional)</span></label>
        <textarea name="alamat_luar_negeri" wire:model='form_data_pemohon.alamat_luar_negeri' id="alamat_luar_negeri" rows="2" placeholder="Mango str. No. 27"
            class="{{ $inputBase }} {{ $errors->has('form_data_pemohon.alamat_luar_negeri') ? $inputErr : $inputOk }}"></textarea>
        @error('form_data_pemohon.alamat_luar_negeri')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
    </div>
</section>
