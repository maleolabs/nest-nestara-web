<div class="vh-30 position-relative">
    <div class="position-absolute z-1 inset-0 w-100 h-100 p-4 vh-30 overflow-hidden">
        <h5 class="text-bold mb-1">
            <i class="fa-regular fa-circle-right me-1"></i>
            Entri Data
        </h5>
        <div class="row">
            @foreach ($entri_data as $entri)
                @if ($entri->tipe == 'textarea')
                    <div class="col-12 mb-3">
                        <div class="input-group input-group-outline">
                            <label class="form-label">
                                {{ $entri->nama }}
                                @if ($entri->mandatori)
                                    <span class="text-danger ms-1">*</span>
                                @endif
                            </label>
                            <textarea class="form-control"></textarea>
                        </div>
                    </div>
                @else
                    <div class="col-md-6 mb-3">
                        <div class="input-group input-group-outline">
                            <label class="form-label">
                                {{ $entri->nama }}
                                @if ($entri->mandatori)
                                    <span class="text-danger ms-1">*</span>
                                @endif
                            </label>
                            <input type="{{ $entri->tipe }}" class="form-control">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="position-absolute inset-0 w-100 h-100 z-2 d-flex flex-column gap-2 align-items-center justify-content-center bg-gradient-danger-up-bottom rounded-3">
        <i class="fa-solid fa-xmark-circle fs-4 text-danger"></i>
        <p class="text-center text-bold text-danger">
            Permohonan izin ini sedang dientri oleh pegawai lain. Anda tidak dapat mengisi formulir ini!
        </p>
    </div>
</div>