<div>
    <div class="d-flex align-items-center gap-2">
        <h4>{{ str_capitalize($preview_jenis->nama, true) }}</h4>
    </div>
    <p class="text-xs text-muted mb-3 pb-1 border-bottom">Detail Perizinan</p>
    <div class="row">
        <div class="col-3 mb-3">
            <p class="fw-semibold mb-0 text-sm text-dark">Kelompok Izin</p>
        </div>
        <div class="col-9 mb-3 d-flex align-items-center">
            <div class="input-group input-group-sm border rounded-3 w-50">
                <select class="form-select border-0" wire:model.lazy='detail_form.kelompok_izin'>
                    <option value="izin tanpa retribusi">Izin Tanpa Retribusi</option>
                    <option value="izin dengan retribusi">Izin Dengan Retribusi</option>
                </select>
            </div>
            <div class="ms-2">
                <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='detail_form.kelompok_izin'></i>
                @if ($detail_form->kelompok_izin != $old_detail_form->kelompok_izin)
                    <span class="text-success message-success-tip text-sm">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        <span class="text-capitalize">berhasil disimpan!</span>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-3 mb-3">
            <p class="fw-semibold mb-0 text-sm text-dark">Durasi Pengerjaan</p>
        </div>
        <div class="col-9 mb-3 d-flex gap-2 align-items-center">
            <div class="input-group input-group-sm border rounded-3 w-fit">
                <input type="number" class="form-control border-0" wire:model.lazy='detail_form.durasi'>
            </div>
            <span class="text-sm">Hari</span>
            <div class="ms-2">
                <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='detail_form.durasi'></i>
                @if ($detail_form->durasi != $old_detail_form->durasi)
                    <span class="text-success message-success-tip text-sm">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        <span class="text-capitalize">berhasil disimpan!</span>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-3">
            <p class="fw-semibold mb-0 text-sm text-dark">Masa Berlaku Izin</p>
        </div>
        <div class="col-9 d-flex gap-2 align-items-center">
            <div class="input-group input-group-sm border rounded-3 w-fit">
                <input type="number" class="form-control border-0" wire:model.lazy='detail_form.masa_berlaku'>
            </div>
            <span class="text-sm">
                Tahun
                {{ $detail_form->masa_berlaku === 0 ? ' (0 artinya berlaku selamanya)' : '' }}
            </span>
            <div class="ms-2">
                <i class="fa-solid fa-spinner fa-spin-pulse" wire:loading wire:target='detail_form.masa_berlaku'></i>
                @if ($detail_form->masa_berlaku != $old_detail_form->masa_berlaku)
                    <span class="text-success message-success-tip text-sm">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        <span class="text-capitalize">berhasil disimpan!</span>
                    </span>
                @endif
            </div>
        </div>
        <div class="col-9 offset-3 mt-3">
            <span class="text-xs">
                <i class="fa-solid fa-circle-info me-1"></i>
                untuk menyimpan tekan enter</span>
        </div>
    </div>
</div>