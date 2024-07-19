<div class="card min-vh-50 p-4 w-100">
    <div class="d-flex align-items-end gap-3 mb-4">
        <div>
            <label for="start_date" class="form-label mb-0">Dari Tanggal</label>
            <div class="input-group input-group-outline">
                <input type="date" class="form-control" id="start_date" name="start_date" wire:model.live='start_date'>
            </div>
        </div>
        <div>
            <label for="end_date" class="form-label mb-0">Sampai Tanggal</label>
            <div class="input-group input-group-outline">
                <input type="date" class="form-control" id="end_date" name="end_date" wire:model.live='end_date'>
            </div>
        </div>
        <div class="input-group input-group-outline">
            <label for="search" class="form-label">cari...</label>
            <input type="text" class="form-control" id="search" name="search" wire:model.live='search'>
        </div>
    </div>

    <div class="table-responsive position-relative">
        <table class="table table-hover table-striped align-items-center mb-0" style="--bs-table-striped-bg: var(--color-primary-extra-light);">
            <thead>
                <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No Pendaftraran</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID Pemohon</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pemohon</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis Izin</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Permohonan</th>
                    @if (isset($table_content))
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ $table_content }}</th>
                    @endif
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($all_izin as $izin)
                    @php
                        $status = $izin->status_izin->last();
                        $statusClasses = getStatusIzinProcessClasses($status)
                    @endphp
                    <tr class="cursor-pointer data-izin" data-izin-id="{{ $izin->id }}" wire:click='previewDataIzin({{ $izin->id }})' @if(isset($preview_action)) {{ $preview_action }} @endif>
                        <td class="align-middle text-center text-sm">{{ $loop->iteration }}</td>
                        <td class="text-sm">
                            <p class="text-xs font-weight-bold mb-0">{{ $izin->no_pendaftaran }}</p>
                        </td>
                        <td class="text-sm">
                            <p class="text-xs font-weight-bold mb-0">{{ $izin->pemohon->nomor_id }}</p>
                        </td>
                        <td>
                            <h6 class="mb-0 text-sm text-wrap">
                                {{ str_capitalize($izin->pemohon->nama_pemohon, true) }}
                            </h6>
                        </td>
                        <td>
                            <p class="text-sm text-secondary mb-0 text-wrap">
                                {{ str_capitalize($izin->jenis_perizinan->nama, true) }}
                            </p>
                        </td>
                        <td class="text-center text-sm">
                            {{ $this->getTanggalPermohonan($izin->id) }}
                        </td>
                        @if (isset($table_content) && isset($izin->no_surat))
                            <td class="text-sm">
                                {{ $izin->no_surat }}
                            </td>
                        @endif
                        <td class="vertical-align-middle">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <span class="badge badge-dot">
                                    <i class="{{ $statusClasses['bgClass'] }}"></i>
                                    <span class="text-sm {{ $statusClasses['textClass'] }}">
                                        {{ Str::limit($status->keterangan, 16) }}
                                    </span>
                                </span>
                                <p class="text-xs text-muted mb-0">{{ $status->updated_at->diffForHumans() }}</p>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ isset($table_content) ? 8 : 7 }}">
                            <div class="w-100 d-flex align-items-center justify-content-center gap-2 vh-30">
                                Tidak ada data izin
                                <i class="fa-regular fa-face-frown"></i>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div wire:loading wire:target='previewDataIzin, start_date, end_date, search'>
            <div class="position-absolute top-0 start-0 h-100 w-100 d-flex align-items-center justify-content-center bg-light opacity-75">
                <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
            </div>
        </div>
    </div>
</div>