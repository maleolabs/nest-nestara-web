<div>
    <p class="text-xs text-muted mb-3 pb-1 border-bottom">Pengaturan Surat</p>
    <div class="row">
        <div class="col-3 mb-3">
            <p class="fw-semibold mb-0 text-sm text-dark">Format Surat</p>
        </div>
        <div class="col-9 mb-3">
            <p class="mb-0 text-sm fw-semibold text-dark">{{ $full_format_surat }}</p>
        </div>
        <div class="col-3 mb-3">
            <p class="fw-semibold mb-0 text-sm text-dark">Format Jenis Surat</p>
        </div>
        <div class="col-9 mb-3 d-flex gap-2 align-items-center">
            <div class="input-group input-group-sm rounded-3 border w-fit">
                <input type="text" class="form-control border-0" wire:model.lazy='format_surat'>
            </div>
            <div class="ms-2">
                <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='format_surat'></i>
                @if ($format_surat != $old_pengaturan_form['format_surat'])
                    <span class="text-success message-success-tip text-sm">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        <span class="text-capitalize">berhasil disimpan!</span>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-3 mb-3">
            <p class="fw-semibold mb-0 text-sm text-dark">Nomor Terakhir Jenis Surat</p>
        </div>
        <div class="col-9 mb-3 d-flex gap-2 align-items-center">
            <div class="input-group input-group-sm rounded-3 border w-fit">
                <input type="number" class="form-control border-0" wire:model.lazy='nomor_jenis_surat'>
            </div>
            <div class="ms-2">
                <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='nomor_jenis_surat'></i>
                @if ($nomor_jenis_surat != $old_pengaturan_form['nomor_jenis_surat'])
                    <span class="text-success message-success-tip text-sm">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        <span class="text-capitalize">berhasil disimpan!</span>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-3">
            <p class="fw-semibold mb-0 text-sm text-dark">Template Surat</p>
        </div>
        <div class="col-9 d-flex gap-2 align-items-center">
            <div class="input-group input-group-sm border rounded-3 w-50">
                <select class="form-select border-0" wire:model.lazy='selected_format'>
                    @foreach ($template_surat as $template)
                        <option value="{{ $template }}">
                            {{ $template }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="ms-2">
                <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='selected_template'></i>
                @if ($selected_template != $old_pengaturan_form['selected_template'])
                    <span class="text-success message-success-tip text-sm">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        <span class="text-capitalize">berhasil disimpan!</span>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-9 offset-3 mt-2">
            <p class="mb-0 text-xs fw-semibold mb-3">
                <span class="badge rounded-pill border bg-light text-capitalize text-dark">default</span>
                {{ $selected_template }}
                @if (!$can_use_template)
                    <span class="ms-1 text-danger">(Template pdf ini tidak bisa digunakan)</span>
                @endif
            </p>
            <span class="text-xs">
                <i class="fa-solid fa-circle-info me-1"></i>
                untuk menyimpan tekan enter</span>
        </div>
    </div>
</div>