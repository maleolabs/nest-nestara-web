<div class="row">
    @if ($menu == self::DATA_PEMOHON)
        @forelse ($semua_pemohon as $pemohon)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="form-card border-0 p-4">
                    <h6 class="text-bold">{{ str_capitalize($pemohon->nama_pemohon, true) }}</h6>
                    <p class="mb-0 text-sm text-bold">
                        <i class="fa-regular fa-id-card me-1"></i>
                        {{ $pemohon->nomor_id }}
                        <span class="text-uppercase">{{ "({$pemohon->sumber_identitas})" }}</span>
                    </p> 
                    <p class="mb-0 text-sm text-bold">
                        <i class="fa-solid fa-phone me-1"></i>
                        {{ $pemohon->no_telepon }}
                    </p> 
                    <div class="d-flex gap-2">
                        <i class="fa-solid fa-location-dot mt-1"></i>
                        <p class="text-capitalize">{{ $this->getAlamat($pemohon->id) }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="form-card border-0 vh-30 d-flex align-items-center justify-content-center">
                    {{ $search != '' ? "'{$search}' tidak ditemukan" : 'Tidak ada data pemohon' }}
                </div>
            </div>
        @endforelse 
    @endif
</div>