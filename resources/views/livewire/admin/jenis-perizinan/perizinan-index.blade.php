@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.jenis-perizinan.index') }}">Jenis Perizinan</a>
    </li>
    <li class="breadcrumb-item active">
        {{ Str::limit(str_capitalize($preview_jenis->nama, true), 70) }}
    </li>
@endpush
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center gap-2">
            <h4>{{ str_capitalize($preview_jenis->nama, true) }}</h4>
            <div class="badge bg-light text-dark rounded-pill border text-capitalize">tersedia</div>
        </div>
        <p class="text-xs text-muted mb-1">Detail Perizinan</p>
        <div class="row">
            <div class="col-3 mb-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Kelompok Izin</p>
            </div>
            <div class="col-9 mb-3">
                <div class="input-group input-group-sm border rounded-3 w-50">
                    <select class="form-select border-0">
                        <option value="izin_tanpa_retribusi">Izin Tanpa Retribusi</option>
                    </select>
                </div>
            </div>
            <div class="col-3 mb-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Durasi Pengerjaan</p>
            </div>
            <div class="col-9 mb-3 d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm border rounded-3 w-fit">
                    <input type="number" class="form-control border-0">
                </div>
                <span class="text-sm">Hari</span>
            </div>
            <div class="col-3">
                <p class="fw-semibold mb-0 text-sm text-dark">Masa Berlaku Izin</p>
            </div>
            <div class="col-9 d-flex gap-2 align-items-center">
                <div class="input-group input-group-sm border rounded-3 w-fit">
                    <input type="number" class="form-control border-0">
                </div>
                <span class="text-sm">Tahun</span>
            </div>
        </div>

        <p class="text-xs text-muted mb-2 mt-4">Persyaratan</p>
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
                        <select wire:model='tipe.{{ $syarat->id }}' id="tipe-{{ $syarat->id }}" class="form-select px-2 border-0">
                            <option value="img">Gambar</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                </div>
                <div class="col-2 d-flex justify-content-center">
                    <div class="input-group input-group-sm rounded-3 border w-fit">
                        <select wire:model='pemasukan_berkas.{{ $syarat->id }}' id="pemasukan_berkas-{{ $syarat->id }}" class="form-select px-2 border-0">
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                            <option value="online_dan_offline">Online dan Offline</option>
                        </select>
                    </div>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                    <button class="btn btn-sm btn-transparent btn-edit shadow-none" type="button">
                        <i class="fa-solid fa-trash text-danger text-sm"></i>
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
            <span class="border rounded-pill px-3 py-2 bg-info-light border-info text-info d-inline w-fit text-sm fw-medium">
                Pas Foto 3x4
                <i class="fa-solid fa-x text-sm ms-2 btn-edit"></i>
            </span>
            <div class="input-group input-group-sm border rounded-3 mt-3 position-relative">
                <input type="text" class="form-control border-0" placeholder="cari atau buat syarat baru..">
                <div class="position-absolute top-100 start-0 w-100 p-3 bg-white z-2 rounded-bottom-3 border border-top-0 d-none">

                </div>
            </div>
            <button class="btn btn-sm mt-3 text-capitalize bg-gradient-primary w-fit">simpan</button>
        </div>

        <p class="text-xs text-muted mb-1 mt-5">Pengaturan Perizinan</p>
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

        <p class="text-xs fw-semibold text-dark mb-1 mt-5">Tindakan</p>
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