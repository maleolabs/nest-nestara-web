@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.jenis-perizinan.index') }}">Jenis Perizinan</a>
    </li>
    <li class="breadcrumb-item active">
        {{ Str::limit(str_capitalize($preview_jenis->nama, true), 70) }}
    </li>
@endpush
<div class="card">
    <md-linear-progress wire:loading wire:target='saveNewSyarat' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="card-body">
        <div class="d-flex align-items-center gap-2">
            <h4>{{ str_capitalize($preview_jenis->nama, true) }}</h4>
            <div class="badge bg-light text-dark rounded-pill border text-capitalize">tersedia</div>
        </div>
        <p class="text-xs text-muted mb-3 pb-1 border-bottom">Detail Perizinan</p>
        <div class="row">
            <div class="col-3 mb-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Kelompok Izin</p>
            </div>
            <div class="col-9 mb-3 d-flex align-items-center">
                <div class="input-group input-group-sm border rounded-3 w-50">
                    <select class="form-select border-0" wire:model.live='detail_form.kelompok_izin'>
                        <option value="izin tanpa retribusi">Izin Tanpa Retribusi</option>
                        <option value="izin dengan retribusi">Izin Dengan Retribusi</option>
                    </select>
                </div>
                <div class="ms-2">
                    <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='detail_form.kelompok_izin'></i>
                    @if ($detail_form->kelompok_izin != $old_detail_form->kelompok_izin)
                        <span class="text-success message-success-tip text-sm">
                            <i class="fa-solid fa-circle-check me-1"></i>
                            <span class="text-capitalize">berhasil disimpan!</span>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-3 mb-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Durasi Pengerjaan</p>
            </div>
            <div class="col-9 mb-3 d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm border rounded-3 w-fit">
                    <input type="number" class="form-control border-0" wire:model.live='detail_form.durasi'>
                </div>
                <span class="text-sm">Hari</span>
                <div class="ms-2">
                    <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='detail_form.durasi'></i>
                    @if ($detail_form->durasi != $old_detail_form->durasi)
                        <span class="text-success message-success-tip text-sm">
                            <i class="fa-solid fa-circle-check me-1"></i>
                            <span class="text-capitalize">berhasil disimpan!</span>
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Masa Berlaku Izin</p>
            </div>
            <div class="col-9 d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm border rounded-3 w-fit">
                    <input type="number" class="form-control border-0" wire:model.live='detail_form.masa_berlaku'>
                </div>
                <span class="text-sm">
                    Tahun
                    {{ $detail_form->masa_berlaku === 0 ? ' (0 artinya berlaku selamanya)' : '' }}
                </span>
                <div class="ms-2">
                    <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='detail_form.masa_berlaku'></i>
                    @if ($detail_form->masa_berlaku != $old_detail_form->masa_berlaku)
                        <span class="text-success message-success-tip text-sm">
                            <i class="fa-solid fa-circle-check me-1"></i>
                            <span class="text-capitalize">berhasil disimpan!</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <p class="text-xs text-muted mb-3 pb-1 border-bottom mt-4">Persyaratan</p>
        @if (count($preview_jenis->syarat_izin) > 0)
            <div class="row mb-3">
                <div class="col-6">
                    <p class="text-bold text-xs mb-0">Nama Syarat</p>
                </div>
                <div class="col-2 d-flex justify-content-center">
                    <p class="text-bold text-xs mb-0">Tipe</p>
                </div>
                <div class="col-2 d-flex justify-content-center">
                    <p class="text-bold text-xs mb-0">Pemasukan Berkas</p>
                </div>
            </div>
        @endif
        @forelse ($preview_jenis->syarat_izin as $syarat)
            <div class="row align-items-center mb-3">
                <div class="col-6">
                    <p class="fw-semibold mb-0 text-dark text-sm d-flex align-items-top gap-1">
                        <span>{{ $loop->iteration . '.' }}</span>
                        {{ str_capitalize($syarat->nama, true) }}
                    </p>
                </div>
                <div class="col-2 d-flex justify-content-center">
                    <div class="input-group input-group-sm rounded-3 border w-fit">
                        <select wire:model.live='tipe.{{ $syarat->id }}' id="tipe-{{ $syarat->id }}" class="form-select px-2 border-0">
                            <option value="img">Gambar</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
                <div class="col-2 d-flex justify-content-center">
                    <div class="input-group input-group-sm rounded-3 border w-fit">
                        <select wire:model.live='pemasukan_berkas.{{ $syarat->id }}' id="pemasukan_berkas-{{ $syarat->id }}" class="form-select px-2 border-0 {{ $pemasukan_berkas[$syarat->id] == 'online' ? 'bg-app-primary-light text-primary' : 'bg-secondary-light text-dark' }}">
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                    <button class="btn btn-sm btn-transparent btn-edit shadow-none" wire:click='deleteSyarat({{ $syarat->id }})' type="button">
                        <i class="fa-solid fa-trash text-danger text-sm" wire:loading.remove wire:target='deleteSyarat({{ $syarat->id }})'></i>
                        <span wire:loading wire:target='deleteSyarat({{ $syarat->id }})' class="fa-solid spinner-border spinner-border-sm"></span>
                    </button>
                </div>
            </div>
        @empty
            <div class="border rounded-3 p-3 text-capitalize text-medium bg-warning-light border-warning text-warning">
                perizinan ini tidak memiliki persyaratan
            </div>
        @endforelse
        <div class="mt-3 d-flex flex-column w-50">
            <p class="fw-medium mb-1">Tambah Persyaratan Baru</p>
            <div class="d-flex align-items-center flex-wrap gap-2">
                @foreach ($new_syarat as $id_new => $new)
                    <span class="border rounded-pill px-3 py-2 mb-2 bg-info-light border-info text-info d-inline w-fit text-sm fw-medium text-wrap">
                        {{ $new }}
                        <i class="fa-solid fa-x text-sm ms-2 btn-edit" wire:click="unsetNewSyarat('{{ $id_new }}')" ></i>
                    </span>
                @endforeach
            </div>
            <div class="input-group input-group-sm border rounded-3 mt-2 position-relative">
                <input type="search" class="form-control border-0" wire:model.lazy='search_new_syarat' placeholder="cari atau buat syarat baru..">
                <div class="position-relative end-0 top-0 z-1 me-3 mt-auto" wire:loading wire:target='search_new_syarat'>
                    <i class="fa-solid fa-spinner fa-spin-pulse"></i>
                </div>
                <div class="position-absolute top-100 start-0 w-100 p-3 bg-white z-2 rounded-bottom-3 border border-top-0{{ count($search_syarat) <=0 ? ' d-none' : '' }}">
                    <ul class="list-group" wire:loading.class='disabled' wire:target='addNewSyarat'>
                        @foreach ($search_syarat as $id_search => $search_result)
                            <li class="list-group-item list-group-item-action cursor-pointer border-0" wire:click="addNewSyarat('{{ $id_search }}', '{{ $search_result }}')">
                                {{ $search_result }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button class="btn btn-sm mt-3 text-capitalize w-fit {{ count($new_syarat) == 0 ? 'disabled bg-gradient-light' : 'bg-gradient-primary' }}" wire:click='saveNewSyarat'>simpan</button>
        </div>

        <p class="text-xs text-muted mb-3 pb-1 border-bottom mt-5">Pengaturan Perizinan</p>
        <div class="row">
            <div class="col-3 mb-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Format Surat</p>
            </div>
            <div class="col-9 mb-3">
                <div class="input-group input-group-sm rounded-3 border w-fit">
                    <input type="text" class="form-control border-0">
                </div>
            </div>
            <div class="col-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Template Surat</p>
            </div>
            <div class="col-9">
                <div class="input-group input-group-sm border rounded-3 w-50">
                    <select class="form-select border-0">

                    </select>
                </div>
            </div>
        </div>

        <p class="text-xs text-muted mb-3 pb-1 border-bottom mt-5">Tindakan</p>
        <div class="border border rounded-3 p-4">
            <div class="row align-items-center mb-3">
                <div class="col-10">
                    <p class="mb-0 text-sm fw-semibold">
                        Ubah nama perizinan "{{ str_capitalize($preview_jenis->nama, true) }}"
                    </p>
                    <small>
                        Perubahan pada nama jenis perizinan ini dapat memiliki dampak terhadap proses administrasi yang terkait. Oleh karena itu, harap berhati-hati dan pertimbangkan dengan matang sebelum melanjutkan.
                    </small>
                </div>
                <button class="btn btn-sm btn-outline-secondary text-capitalize text-nowrap col-2" type="button">
                    ubah
                </button>
            </div>
            <div class="row align-items-center">
                <div class="col-10 text-danger">
                    <p class="mb-0 text-sm fw-semibold">
                        Hapus perizinan "{{ str_capitalize($preview_jenis->nama, true) }}"
                    </p>
                    <small>
                        Tindakan ini bersifat permanen dan dapat mempengaruhi berbagai aspek operasional dan administratif. Harap berhati-hati dan pertimbangkan dengan matang sebelum melanjutkan.
                    </small>
                </div>
                <button class="btn btn-sm btn-outline-danger text-capitalize text-nowrap col-2" type="button">
                    hapus
                </button>
            </div>
        </div>
    </div>
</div>