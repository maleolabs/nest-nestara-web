<div class="modal-right d-none">
    <div class="modal-right-content d-flex">
        <div>
            <h4 class="mb-4">Lihat pendaftaran izin</h4>
            @if ($selectedPreview)
                <p class="mb-0 text-sm">Jenis perizinan</p>
                <h3>{{ $selectedPreview->jenis_perizinan->nama }}</h3>
                <p class="mb-0 text-sm">Kelompok Izin</p>
                <h6>Izin tanpa retribusi</h6>
                <h6 class="mb-0">
                    <i class="fa-solid fa-hourglass-half me-1"></i>
                    Durasi pengerjaan 1 hari
                </h6>
                <h6 class="mb-0">
                    <i class="fa-regular fa-calendar-check me-1"></i>
                    Masa berlaku izin 5 tahun
                </h6>
                <p class="mt-3 mb-0 text-muted">Status izin</p>
                <div class="d-flex align-items-top gap-2">
                    <i class="fa-regular fa-circle-check mt-1 text-success"></i>
                    <div>
                        <p class="mb-0 text-success">Telah didaftarkan</p>
                        <p class="mb-0">{{ $selectedPreview->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
                <p class="mt-3 mb-0 text-muted">Persyaratan</p>
                @foreach ($selectedPreviewSyaratIzin as $syarat)
                    <h6>{{ $syarat->nama }}</h6>
                @endforeach
            @endif
        </div>
        <button class="ms-auto text-md btn p-0 card-icon rounded-circle" id="modalRightCloseBtn">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>