<div class="modal-right d-none">
    <div class="modal-right-content modal-right-content-md d-flex">
        <div class="w-100">
            <h4 class="mb-4 spacing-md">Penetapan Izin</h4>
            @if ($selectedPreview)
                <p class="mb-0 text-sm">Nama Izin</p>
                <h3 class="spacing-md">{{ str_capitalize($selectedPreview->jenis_perizinan->nama, true) }}</h3>
                <p class="mb-0 text-sm">Pemohon</p>
                <h6 class="text-capitalize">{{ $selectedPreview->pemohon->nama_pemohon }}</h6>
                <p class="mb-0 text-sm">Lokasi Izin</p>
                <h6 class="text-capitalize">{{ $selectedPreview->lokasi_izin }}</h6>
                <p class="mb-0 text-sm">Nomor Surat</p>
                <h6 class="text-capitalize">{{ $selectedPreview->no_surat }}</h6>
                <p class="mb-0 text-sm">Penandatangan</p>
                <h6 class="text-capitalize">{{ $selectedPreview->penandatangan->nama }}</h6>
                <p class="mt-3 mb-2 text-sm">Riwayat Proses</p>
                <div class="row justify-content-start mb-4">
                    @foreach ($previewRiwayatProses as $proses => $data)
                        <div class="col-md-5 mb-2">
                            <p class="text-sm mb-0 text-bold">{{ $proses }}</p>
                        </div>
                        <div class="col-md-5 mb-2">
                            <p class="mb-0{{ $data['status'] ? ' text-success' : ' text-warning' }}">
                                <i class="fa-solid {{ $data['status'] ? 'fa-circle-check' : 'fa-circle-exclamation' }} me-1"></i>
                                {{ isset($data['keterangan']) ? $data['keterangan'] : 'selesai' }}
                            </p>
                            <p class="text-sm text-bold mb-0">
                                <i class="fa-solid fa-user-tie me-2"></i>
                                {{ $data['pegawai'] }}
                            </p>
                            <p class="mb-0 text-sm text-m">
                                <i class="fa-regular fa-calendar-check me-2"></i>
                                {{ $data['tanggal'] }}
                            </p>
                        </div>
                        <hr class="mx-2">
                    @endforeach
                </div>
                @if (in_array($selectedPreview->status_izin->last()->keterangan, [App\utils\enums\BackOfficeStatusEnum::DIREKOM, App\utils\enums\BackOfficeStatusEnum::REKOM_CATATAN]))
                    <div class="rounded-4 border p-4">
                        <div class="mb-4">
                            <form wire:submit.prevent='save'>
                                <p class="text-sm text-bold">Tindakan Izin</p>
                                <div class="d-flex align-items-center">
                                    <div class="form-check ps-0">
                                        <input type="radio" class="form-check-input" name="tindakan" id="izinkan" value="{{ App\utils\enums\BackOfficeStatusEnum::DIIZINKAN }}" wire:model='tindakan'>
                                        <label class="form-check-label" for="izinkan">Izinkan</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="tindakan" id="tolak" value="{{ App\utils\enums\BackOfficeStatusEnum::LAYANAN_DITOLAK }}" wire:model='tindakan'>
                                        <label class="form-check-label" for="tolak">Tolak Layanan</label>
                                    </div>
                                </div>
                                @error('tindakan')
                                    <div class="text-sm text-danger">{{ $message }}</div>
                                @enderror
                                <button class="btn btn-sm bg-gradient-primary mt-3" type="submit">simpan</button>
                            </form>
                        </div>
                        <div class="bg-app-primary-light rounded-4 p-4 text-sm">
                            Pastikan Anda memilih tindakan (izinkan atau tolak) untuk menyelesaikan proses permohonan izin ini.
                        </div>
                    </div>
                @elseif ($selectedPreview->status_izin->last()->keterangan == App\utils\enums\BackOfficeStatusEnum::DIIZINKAN)
                    <a href="{{ route('download-file', ['file_name' => 'test']) }}" target="blank" class="btn btn-sm bg-gradient-info">
                        <i class="fa-solid fa-download me-1 fs-6"></i>
                        Unduh Surat Izin
                    </a>
                @endif
                <div class="p-3"></div>
            @endif
        </div>
        <button class="ms-auto text-md btn btn-transparent p-0 card-icon sticky-top-0" wire:click='closePreview' id="modalRightCloseBtn">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div wire:loading wire:target='previewDataIzin, save'>
            <div class="position-fixed top-0 end-0 bottom-0 bg-white opacity-75 d-flex align-items-center justify-content-center modal-right-loading">
                <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
            </div>
        </div>
    </div>
</div>