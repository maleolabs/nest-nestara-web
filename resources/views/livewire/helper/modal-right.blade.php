<div class="modal-right d-none">
    <div class="modal-right-content d-flex">
        <div>
            <h4 class="mb-4 spacing-md">Lihat pendaftaran izin</h4>
            @if ($selectedPreview)
                <p class="mb-0 text-sm">Jenis perizinan</p>
                <h3 class="spacing-md">{{ str_capitalize($selectedPreview->jenis_perizinan->nama, true) }}</h3>
                <p class="mb-0 text-sm">Kelompok Izin</p>
                <h6 class="text-capitalize">{{ $selectedPreview->jenis_perizinan->kelompok_izin }}</h6>
                <h6 class="mb-0">
                    <i class="fa-solid fa-hourglass-half me-1"></i>
                    Durasi pengerjaan {{ $selectedPreview->jenis_perizinan->durasi }}
                </h6>
                <h6 class="mb-0">
                    <i class="fa-regular fa-calendar-check me-1"></i>
                    Masa berlaku izin {{ $selectedPreview->jenis_perizinan->masa_berlaku }}
                </h6>
                <p class="mt-3 mb-0 text-muted">Status izin</p>
                <div>
                    @foreach ($selectedPreview->status_izin as $status)
                        <div class="d-flex align-items-top gap-2 mb-3 icon-status-wraper">
                            @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                <i class="fa-regular fa-circle-check mt-1 text-success"></i>
                            @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED || $status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                <i class="fa-solid fa-hourglass-half mt-1 text-dark"></i>
                            @elseif ($status->progres == App\utils\enums\StatusIzinEnum::BATAL || $status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                <i class="fa-solid fa-circle-xmark mt-1 text-danger"></i>
                            @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                <i class="fa-solid fa-triangle-exclamation mt-1 text-warning"></i>
                            @endif
                            <div>
                                <p class="mb-0 @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                        text-success
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED)
                                        text-muted
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                        text-dark
                                    @elseif ($status->progres == App\utils\enums\StatusIzinEnum::BATAL || $status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                        text-danger
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                        text-warning
                                    @endif">
                                    {{ $status->nama }}
                                </p>
                                <p class="mb-0">{{ $status->created_at->translatedFormat('l, d F Y H:i') . ' WITA' }}</p>
                                @if ($status->created_at != $status->updated_at)
                                    <p class="mb-0 text-sm">
                                        <i class="fa-solid fa-rotate me-1"></i>
                                        {{ 'Terakhir diupdate ' .$status->updated_at->translatedFormat('l, d F Y H:i') . ' WITA' }}
                                    </p>
                                @endif
                                @if ($status->keterangan && $status->nama != App\utils\enums\StatusIzinEnum::PROSES)
                                    <small class="text-muted text-sm fst-italic">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        {{ $status->keterangan }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="rounded-3 bg-app-primary-light p-3 mt-3">
                    <strong>Informasi:</strong>
                    <p class="mb-0">
                        Dokumen Anda akan divalidasi terlebih dahulu, kemudian akan diproses oleh petugas, dan izin Anda akan diterbitkan setelah semua tahap selesai.
                    </p>
                </div>  
                <p class="mt-3 mb-0 text-muted">Persyaratan</p>
                @foreach ($selectedPreviewSyaratIzin as $syarat)
                    <h6 @if(!isset($syarat->dokumen_pemohon[0]))class="text-muted text-decoration-line-through"@endif>
                        @if (isset($syarat->dokumen_pemohon[0]))
                            <a href="{{ route('download-file', ['file_name' => $syarat->dokumen_pemohon[0]->nama_file]) }}" class="text-decoration-none d-inline">
                                <i class="fa-solid fa-download me-1"></i>
                            </a>
                        @else
                            <i class="fa-solid fa-download me-1"></i>
                        @endif
                        {{ $syarat->nama }}
                    </h6>
                @endforeach
                <a href="javascript:;">
                    lihat semua dokumen anda
                    <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                </a>
            @endif
            <div class="p-3"></div>
        </div>
        <button class="ms-auto text-md btn p-0 card-icon rounded-circle" wire:click='closePreview' id="modalRightCloseBtn">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div wire:loading wire:target='preview'>
        <div class="position-absolute top-0 end-0 bottom-0 bg-white opacity-75 d-flex align-items-center justify-content-center" style="width: 700px">
            <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
        </div>
    </div>
</div>