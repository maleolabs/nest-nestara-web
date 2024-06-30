<div class="table-responsive">
    <table class="table align-items-center mb-0">
        <thead>
        <tr>
            @if ($menu == self::DATA_PEMOHON)
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
                <th class="text-secondary opacity-7"></th>
            @endif
        </tr>
        </thead>
        <tbody>
            @if ($menu == self::DATA_PEMOHON)
                @forelse ($semua_pemohon as $pemohon)
                    <tr>
                        <td class="align-middle text-center text-sm">{{ $loop->iteration }}</td>
                        <td class="text-sm">
                            <p class="text-xs font-weight-bold mb-0">{{ $pemohon->nomor_id }}</p>
                        </td>
                        <td>
                            <h6 class="mb-0 text-sm">
                                {{ str_capitalize($pemohon->nama_pemohon, true) }}
                            </h6>
                        </td>
                        <td>
                            <p class="text-sm text-secondary mb-0 text-capitalize">
                                {{ $this->getAlamat($pemohon->id) }}
                            </p>
                        </td>
                        <td>
                            <a href="javascript:;" class="btn btn-sm btn-light">
                                <i class="fa-solid fa-square-pen fs-6 me-1"></i>
                                Edit Data
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted text-sm">
                            {{ $search != '' ? "'{$search}' tidak ditemukan" : 'Tidak ada data pemohon' }}
                        </td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>
</div>