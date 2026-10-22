<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="min-w-0">
        <div class="border border-2 border-success rounded-lg bg-nest-50 text-nest-700 ring-1 ring-nest-200 p-3 mb-4 text-emerald-600">
            <span class="font-bold">Sukses! </span>
            <span class="text-sm">
                Data pemohon baru berhasil disimpan. Terima kasih telah memperbarui informasi pemohon. Semua data telah dicatat dan disimpan dengan aman. Anda sekarang dapat melanjutkan untuk menambahkan data pemohon lainnya atau kembali ke daftar pemohon untuk melihat data yang baru saja ditambahkan.
            </span>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-3">
            <div class="min-w-0 mb-4">
                <div class="rounded-lg border p-3 relative">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-sky-600 text-white absolute top-0 translate-middle ms-sm-3 ms-4" style="left: 5%">
                        <i class="fa-solid fa-id-card me-1"></i>
                        ID
                    </span>
                    <div class="grid grid-cols-1 gap-4 mt-2">
                        <p class="min-w-0 text-ink-500 mb-3 text-sm">Sumber Identitas</p>
                        <h6 class="min-w-0 @if(is_value_exists($form_data_pemohon->sumber_identitas))font-bold uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->sumber_identitas))
                                {{ $form_data_pemohon->sumber_identitas }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="min-w-0 text-ink-500 text-sm mb-0">Nomor Identitas</p>
                        <h6 class="min-w-0 @if(is_value_exists($form_data_pemohon->nomor_id))font-bold uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->nomor_id))
                                {{ $form_data_pemohon->nomor_id }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
            <div class="min-w-0 mb-4">
                <div class="rounded-lg border p-3 relative">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-sky-600 text-white absolute top-0 translate-middle ms-sm-4 ms-5" style="left: 15%">
                        <i class="fa-solid fa-address-book me-1"></i>
                        Informasi Kontak
                    </span>
                    <div class="grid grid-cols-1 gap-4 mt-2">
                        <p class="min-w-0 text-ink-500 text-sm mb-3">Nama Pemohon</p>
                        <h6 class="min-w-0 @if(is_value_exists($form_data_pemohon->nama_pemohon))font-bold uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->nama_pemohon))
                                {{ $form_data_pemohon->nama_pemohon }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="min-w-0 text-ink-500 text-sm mb-0">Nomor Telepon</p>
                        <h6 class="min-w-0 @if(is_value_exists($form_data_pemohon->no_telepon))font-bold uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->no_telepon))
                                {{ $form_data_pemohon->no_telepon }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                    </div>
                </div>
            </div>
            <div class="min-w-0 mb-4">
                <div class="rounded-lg border p-3 relative">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold bg-sky-600 text-white absolute top-0 translate-middle ms-sm-2 ms-5" style="left: 5%">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        Alamat
                    </span>
                    <div class="grid grid-cols-1 gap-4 mt-2">
                        <p class="col-sm-3 text-ink-500 text-sm mb-3">Provinsi</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->provinsi))font-bold uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->provinsi))
                                {{ $this->getRegionValue('provinsi', $form_data_pemohon->provinsi) }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-ink-500 text-sm mb-3">Kabupaten</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->kabupaten))font-bold uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->kabupaten))
                                {{ $this->getRegionValue('kabupaten', $form_data_pemohon->kabupaten) }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-ink-500 text-sm mb-3">Kecamatan</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->kecamatan))font-bold uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->kecamatan))
                                {{ $this->getRegionValue('kecamatan', $form_data_pemohon->kecamatan) }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-ink-500 text-sm mb-3">Kelurahan/Desa</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->kelurahan))font-bold uppercase @endif text-sm mb-3">
                            @if (is_value_exists($form_data_pemohon->kelurahan))
                                {{ $this->getRegionValue('kelurahan', $form_data_pemohon->kelurahan) }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-ink-500 text-sm mb-0">Detail Alamat</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->detail_alamat))font-bold uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->detail_alamat))
                                {{ $form_data_pemohon->detail_alamat }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
                            @endif
                        </h6>
                        <p class="col-sm-3 text-ink-500 text-sm mb-0">Alamat Luar Negeri</p>
                        <h6 class="col-sm-3 @if(is_value_exists($form_data_pemohon->alamat_luar_negeri))font-bold uppercase @endif text-sm mb-0">
                            @if (is_value_exists($form_data_pemohon->alamat_luar_negeri))
                                {{ $form_data_pemohon->alamat_luar_negeri }}
                            @else
                                <i class="text-sm text-ink-500">Tidak ada</i>
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
        <div class="flex items-center justify-between gap-2">
            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white w-full" title="Tambah data pemohon baru" wire:click='addNew'>
                <i class="fa-solid fa-square-plus text-base" wire:loading.remove wire:target='addNew'></i>
                <span wire:loading wire:target='addNew'>
                    <div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>
                <span class="ml-1 d-sm-inline hidden">Tambah data baru</span>
            </button>
            <a href="{{ route('layanan.pendaftaran') }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-sky-600 text-white w-full" title="Lihat semua data pemohon">
                <i class="fa-solid fa-eye text-base"></i>
                <span class="ml-1 d-sm-inline hidden">Lihat semua data</span>
            </a>
            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-ink-100 text-ink-800 w-full" title="Ubah data pemohon" wire:click='editData'>
                <i class="fa-solid fa-square-pen text-ink-500 text-base" wire:loading.remove wire:target='editData'></i>
                <span wire:loading wire:target='editData'>
                    <div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-ink-300 border-t-ink-800" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </span>
                <span class="ml-1 d-sm-inline hidden">Ubah data pemohon</span>
            </button>
            <div x-data="{ open: false }" class="w-full">
            <button type="button" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-red-600 text-white w-full" title="Hapus data pemohon" @click="open = true" :aria-expanded="open.toString()" aria-controls="modal-delete-data-pemohon">
                <i class="fa-solid fa-trash text-base"></i>
                <span class="ml-1 d-sm-inline hidden">Hapus data pemohon</span>
            </button>
            <div x-cloak x-show="open" @keydown.escape.window="open = false" @click.self="open = false" id="modal-delete-data-pemohon" class="fixed inset-0 z-50 flex items-center justify-center bg-ink-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="modal-title-notification">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                    <div class="flex items-start justify-between gap-4">
                        <h2 class="text-lg font-bold text-ink-900" id="modal-title-notification">Hapus data pemohon?</h2>
                        <button type="button" class="text-ink-500 hover:text-ink-900" @click="open = false" aria-label="Tutup">×</button>
                    </div>
                    <p class="py-5 text-sm text-ink-600">Data pemohon akan dihapus. Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-ink-100 text-ink-800" @click="open = false">Batal</button>
                        <button type="button" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-red-600 text-white" wire:click="deleteData" @click="open = false">Hapus</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <hr>
    </div>
</div>
