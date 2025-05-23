<div class="modal fade" id="showDocumentImgModal" tabindex="-1" aria-labelledby="showDocumentImgModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h1 class="modal-title fs-5" id="showDocumentImgModalLabel">{{ $modalImgTitle }}</h1>
                @if ($modalPdfSource)
                    <p class="mb-0 text-muted">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        PDF ini dalam mode <span class="fst-italic">read only</span>.
                    </p>
                @endif
            </div>
            <div class="modal-body">
                @if ($modalImgSource)
                    <img src="{{ route('render-file', ['file_name' => $modalImgSource]) }}" class="img-fluid">
                @elseif($modalPdfSource)
                    <div class="d-flex justify-content-center">
                        <div id="pdf-container"></div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-close-modal">Tutup</button>
            </div>
        </div>
    </div>
</div>