<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    @if ($menu == self::DATA_PEMOHON)
        @forelse ($semua_pemohon as $pemohon)
            <div class="min-w-0 mb-4">
                <div class="form-card border-0 p-4">
                    <h6 class="font-bold">{{ str_capitalize($pemohon->nama_pemohon, true) }}</h6>
                    <p class="mb-0 text-sm font-bold">
                        <i class="fa-regular fa-id-card me-1"></i>
                        {{ $pemohon->nomor_id }}
                        <span class="uppercase">{{ "({$pemohon->sumber_identitas})" }}</span>
                    </p> 
                    <p class="mb-0 text-sm font-bold">
                        <i class="fa-solid fa-phone me-1"></i>
                        {{ $pemohon->no_telepon }}
                    </p> 
                    <div class="flex gap-2">
                        <i class="fa-solid fa-location-dot mt-1"></i>
                        <p class="capitalize">{{ $this->getAlamat($pemohon->id) }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="min-w-0">
                <div class="form-card border-0 min-h-[30vh] flex items-center justify-center">
                    {{ $search != '' ? "'{$search}' tidak ditemukan" : 'Tidak ada data pemohon' }}
                </div>
            </div>
        @endforelse 
    @endif
</div>
