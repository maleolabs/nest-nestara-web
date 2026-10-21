@if (count($preview_izin_dokumen) > 0)
    <div class="rounded-3 border border-danger bg-danger-light p-3 my-3 text-danger">
        <strong>Penting:</strong>
        <p class="mb-0">
            Dokumen ini bersifat sangat sensitif dan rahasia. Pastikan untuk tidak menyimpan atau mengunduhnya di tempat yang tidak aman atau diakses oleh pihak yang tidak berwenang.
        </p>
    </div>
    @foreach ($preview_izin_dokumen as $dokumen)
        <h6>
            <a href="{{ \App\Support\SignedFileUrl::make($dokumen->nama_file) }}" class="text-decoration-none d-inline">
                <i class="fa-solid fa-download me-1"></i>
            </a>
            <a href="javascript:;" class="text-decoration-none d-inline" wire:click="showImgModal('{{ $dokumen->syarat_izin->nama }}', '{{ base64_encode($dokumen->nama_file) }}', '{{ $dokumen->syarat_izin->tipe }}', {{ $dokumen->syarat_izin_id }})">
                <i class="fa-solid fa-eye me-1"></i>
            </a>
            <button type="button" id="btnShowPreviewModal" data-bs-toggle="modal" data-bs-target="#showDocumentImgModal" class="opacity-0"></button>
            {{ $dokumen->syarat_izin->nama }}
        </h6>
    @endforeach
    @foreach ($preview_izin_syarat_izin as $syarat)
        <h6 class="text-muted text-decoration-line-through">
            <i class="fa-solid fa-download me-1"></i>
            <i class="fa-solid fa-eye me-1"></i>
            {{ $syarat->nama }}>
        </h6>
    @endforeach
@else
    <div class="alert alert-light">
        <p class="mt-3 text-muted">
            <i class="fa-solid fa-circle-info me-1"></i>
            Tidak ada persyaratan untuk perizinan ini!
        </p>
    </div>
@endif
