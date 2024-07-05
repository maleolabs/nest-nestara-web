<div class="row">
    <div class="col-12">
        <div class="border border-2 border-success rounded bg-app-primary-light p-3 mb-4 text-success">
            <span class="text-bold">Sukses! </span>
            <span class="text-sm">
                Data pemohon baru berhasil disimpan. Terima kasih telah memperbarui informasi pemohon. Semua data telah dicatat dan disimpan dengan aman. Anda sekarang dapat melanjutkan untuk menambahkan data pemohon lainnya atau kembali ke daftar pemohon untuk melihat data yang baru saja ditambahkan.
            </span>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6 mb-4">
                <div class="rounded border p-3 position-relative">
                    <span class="badge bg-gradient-info position-absolute top-0 translate-middle ms-sm-3 ms-4" style="left: 5%">
                        <i class="fa-solid fa-id-card me-1"></i>
                        ID
                    </span>
                    <div class="row mt-2">
                        <p class="col-sm-6 text-muted mb-3 text-sm">Sumber Identitas</p>
                        <h6 class="col-sm-6 @if(is_value_exists($form_data_pemohon->sumber_identitas))text-bold text-uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->sumber_identitas))
                                {{ $form_data_pemohon->sumber_identitas }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-6 text-muted text-sm mb-0">Nomor Identitas</p>
                        <h6 class="col-sm-6 @if(is_value_exists($form_data_pemohon->nomor_id))text-bold text-uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->nomor_id))
                                {{ $form_data_pemohon->nomor_id }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 mb-4">
                <div class="rounded border p-3 position-relative">
                    <span class="badge bg-gradient-info position-absolute top-0 translate-middle ms-sm-4 ms-5" style="left: 15%">
                        <i class="fa-solid fa-address-book me-1"></i>
                        Informasi Kontak
                    </span>
                    <div class="row mt-2">
                        <p class="col-sm-6 text-muted text-sm mb-3">Nama Pemohon</p>
                        <h6 class="col-sm-6 @if(is_value_exists($form_data_pemohon->nama_pemohon))text-bold text-uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->nama_pemohon))
                                {{ $form_data_pemohon->nama_pemohon }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-6 text-muted text-sm mb-0">Nomor Telepon</p>
                        <h6 class="col-sm-6 @if(is_value_exists($form_data_pemohon->no_telepon))text-bold text-uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->no_telepon))
                                {{ $form_data_pemohon->no_telepon }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="rounded border p-3 position-relative">
                    <span class="badge bg-gradient-info position-absolute top-0 translate-middle ms-sm-2 ms-5" style="left: 5%">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        Alamat
                    </span>
                    <div class="row mt-2">
                        <p class="col-sm-3 text-muted text-sm mb-3">Provinsi</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->provinsi))text-bold text-uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->provinsi))
                                {{ $this->getRegionValue('provinsi', $form_data_pemohon->provinsi) }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-muted text-sm mb-3">Kabupaten</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->kabupaten))text-bold text-uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->kabupaten))
                                {{ $this->getRegionValue('kabupaten', $form_data_pemohon->kabupaten) }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-muted text-sm mb-3">Kecamatan</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->kecamatan))text-bold text-uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->kecamatan))
                                {{ $this->getRegionValue('kecamatan', $form_data_pemohon->kecamatan) }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-muted text-sm mb-3">Kelurahan/Desa</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->kelurahan))text-bold text-uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->kelurahan))
                                {{ $this->getRegionValue('kelurahan', $form_data_pemohon->kelurahan) }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-muted text-sm mb-0">Detail Alamat</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->detail_alamat))text-bold text-uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->detail_alamat))
                                {{ $form_data_pemohon->detail_alamat }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-muted text-sm mb-0">Alamat Luar Negeri</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->alamat_luar_negeri))text-bold text-uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->alamat_luar_negeri))
                                {{ $form_data_pemohon->alamat_luar_negeri }}
                            @else
                                <i class="text-sm text-muted">Tidak ada</i>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <strong>Info:</strong>
        <p class="text-xs">
            Untuk mengelola data pemohon, Anda bisa menambah data baru, mengedit data yang sudah ada, atau menghapus data yang dipilih. Gunakan tombol di bawah ini sesuai kebutuhan Anda.
        </p>
        <div class="d-flex align-items-center justify-content-between gap-2">
            <button class="btn bg-gradient-primary w-100" title="Tambah data pemohon baru" wire:click='addNew'>
                <i class="fa-solid fa-square-plus text-md" wire:loading.remove wire:target='addNew'></i>
                <span wire:loading wire:target='addNew'>
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>
                <span class="ms-1 d-sm-inline d-none">Tambah data baru</span>
            </button>
            <a href="{{ route('pendaftaran.index') }}" class="btn bg-gradient-info w-100" title="Lihat semua data pemohon">
                <i class="fa-solid fa-eye text-md"></i>
                <span class="ms-1 d-sm-inline d-none">Lihat semua data</span>
            </a>
            <button class="btn bg-gradient-light w-100" title="Ubah data pemohon" wire:click='editData'>
                <i class="fa-solid fa-square-pen text-secondary text-md" wire:loading.remove wire:target='editData'></i>
                <span wire:loading wire:target='editData'>
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>
                <span class="ms-1 d-sm-inline d-none">Ubah data pemohon</span>
            </button>
            <button class="btn bg-gradient-danger w-100" title="Hapus data pemohon" data-bg-toggle="modal" data-bs-target="#modal-delete-data-pemohon">
                <i class="fa-solid fa-trash text-md"></i>
                <span class="ms-1 d-sm-inline d-none">Hapus data pemohon</span>
            </button>
            <div class="modal fade" id="modal-delete-data-pemohon" tabindex="-1" role="dialog" aria-labelledby="modal-delete-data-pemohon" aria-hidden="true">
                <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title font-weight-normal" id="modal-title-notification">Your attention is required</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="py-3 text-center">
                            <i class="material-icons h1 text-secondary">
                                notifications_active
                            </i>
                            <h4 class="text-gradient text-danger mt-4">You should read this!</h4>
                            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white">Ok, Got it</button>
                            <button type="button" class="btn btn-link text-white ml-auto" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</div>