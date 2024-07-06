<div class="table-responsive position-relative">
    <table class="table table-hover table-striped align-items-center mb-0" style="--bs-table-striped-bg: var(--color-primary-extra-light);">
        <thead>
        <tr>
            @if ($menu == self::DATA_PEMOHON)
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
                <th class="text-secondary opacity-7"></th>
            @elseif ($menu == self::DATA_IZIN_BARU)
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Pendaftraran</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Pemohon</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pemohon</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Izin</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Izin</th>
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
                        <td class="d-flex align-items-center gap-2">
                            @if($pemohon->user != null)
                                <img src="{{ asset('storage/users/' . $pemohon->user->foto) }}" alt="{{ $pemohon->nama_pemohon }}" class="img-circle img-circle-sm">
                            @endif
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
            @elseif ($menu == self::DATA_IZIN_BARU)
                @forelse ($semua_izin as $izin)
                    <tr class="cursor-pointer data-izin" data-izin-id="{{ $izin->id }}" wire:click='previewDataIzin({{ $izin->id }})'>
                        <td class="align-middle text-center text-sm">{{ $loop->iteration }}</td>
                        <td class="text-sm">
                            <p class="text-xs font-weight-bold mb-0">{{ $izin->no_pendaftaran }}</p>
                        </td>
                        <td class="text-sm">
                            <p class="text-xs font-weight-bold mb-0">{{ $izin->pemohon->nomor_id }}</p>
                        </td>
                        <td>
                            <h6 class="mb-0 text-sm">
                                {{ str_capitalize($izin->pemohon->nama_pemohon, true) }}
                            </h6>
                        </td>
                        <td>
                            <p class="text-sm text-secondary mb-0">
                                {{ str_capitalize($izin->jenis_perizinan->nama, true) }}
                            </p>
                        </td>
                        <td class="d-flex flex-column align-items-center">
                            @php
                                $status = $izin->status_izin->last()
                            @endphp
                            <span class="badge badge-dot">
                                @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                    <i class="bg-success"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED)
                                    <i class="bg-dark"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                    <i class="bg-info"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                    <i class="bg-danger"></i>
                                @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                    <i class="bg-warning"></i>
                                @endif
                                <span class="text-sm 
                                    @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                        text-success
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED)
                                        text-muted
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                        text-dark
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                        text-danger
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                        text-warning
                                    @endif">
                                    {{ $status->nama != App\utils\enums\StatusIzinEnum::DIDAFTARKAN ? "{$status->nama} ({$status->progres})" : $status->nama }}
                                </span>
                            </span>
                            <p class="text-xs text-muted mb-0">{{ $status->updated_at->diffForHumans() }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-sm text-muted py-3" colspan="6">tidak ada data</td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>
    <div wire:loading wire:target='previewDataIzin'>
        <div class="position-absolute top-0 start-0 h-100 w-100 d-flex align-items-center justify-content-center bg-light opacity-75">
            <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
        </div>
    </div>
</div>