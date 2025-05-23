<div class="table-responsive position-relative">
    <table class="table table-hover table-striped align-items-center mb-0" style="--bs-table-striped-bg: var(--color-primary-extra-light);">
        <thead>
        <tr>
            @if ($menu == self::DATA_PEMOHON)
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
            @elseif ($menu == self::DATA_IZIN_BARU)
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Pendaftraran</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Pemohon</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pemohon</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Izin</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Permohonan</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Izin</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pegawai yang meninjau</th>
            @endif
        </tr>
        </thead>
        <tbody>
            @if ($menu == self::DATA_PEMOHON)
                @forelse ($semua_pemohon as $pemohon)
                    <tr class="cursor-pointer data-pemohon" data-pemohon-id="{{ $pemohon->id }}" wire:click='previewPemohon({{ $pemohon->id }})'>
                        <td class="align-middle ps-4 text-sm">{{ $loop->iteration }}</td>
                        <td class="text-sm">
                            <p class="text-xs font-weight-bold mb-0">{{ $pemohon->nomor_id }}</p>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/users/' . ($pemohon->user ? $pemohon->user->foto : 'profile.png')) }}" alt="{{ $pemohon->nama_pemohon }}" class="img-circle img-circle-xs">
                                
                                <h6 class="mb-0 text-sm">
                                    {{ str_capitalize($pemohon->nama_pemohon, true) }}
                                </h6>
                            </div>
                        </td>
                        <td>
                            <p class="text-sm text-secondary mb-0 text-capitalize">
                                {{ $this->getAlamat($pemohon->id) }}
                            </p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-muted text-sm">
                            <div class="vh-30 d-flex align-items-center justify-content-center">
                                {{ $search != '' ? "'{$search}' tidak ditemukan" : 'Tidak ada data pemohon' }}
                            </div>
                        </td>
                    </tr>
                @endforelse
            @elseif ($menu == self::DATA_IZIN_BARU)
                @forelse ($semua_izin as $izin)
                    @php
                        $status = $izin->status_izin->last();
                        $statusClasses = getStatusIzinClasses($status)
                    @endphp
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
                        <td class="text-center text-sm">
                            {{ $this->getTanggalPermohonan($izin->id) }}
                        </td>
                        <td class="d-flex flex-column align-items-center">
                            <span class="badge badge-dot">
                                <i class="{{ $statusClasses['bgClass'] }}"></i>
                                <span class="text-sm {{ $statusClasses['textClass'] }}">
                                    {{ $status->nama }}
                                </span>
                            </span>
                            <p class="text-xs text-muted mb-0">{{ $status->updated_at->diffForHumans() }}</p>
                        </td>
                        <td>
                            <p class="text-wrap text-sm text-bold text-center mb-0">
                                {{ $status->pegawai ? $status->pegawai->nama : 'Belum ada pegawai yang meninjau' }}
                            </p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-sm text-muted py-3" colspan="8">
                            <div class="vh-30 d-flex align-items-center justify-content-center">
                                tidak ada data
                            </div>
                        </td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>
    <div wire:loading wire:target='previewDataIzin, previewPemohon, start_date, end_date, search'>
        <div class="position-absolute top-0 start-0 h-100 w-100 d-flex align-items-center justify-content-center bg-light opacity-75">
            <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
        </div>
    </div>
</div>