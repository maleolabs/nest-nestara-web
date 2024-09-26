<form wire:submit.prevent='saveEntri'>
    <div class="row">
        @foreach ($entri_data as $entri)
            @if ($entri->tipe == 'textarea')
                <div class="{{ $loop->iteration == 1 || $loop->iteration == count($entri_data) ? 'col-12' : 'col-md-6' }} mb-3">
                    <div class="input-group input-group-outline">
                        <label for="{{ $entri->id }}" class="form-label">
                            {{ $entri->nama }}
                            @if ($entri->mandatori)
                                <span class="text-danger ms-1">*</span>
                            @endif
                        </label>
                        <textarea name="{{ $entri->id }}" class="form-control {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }} @error('entri_data_izin.' . $entri->id)is-invalid @enderror" id="{{ $entri->id }}" rows="3" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}></textarea>
                    </div>
                    @error('entri_data_izin.' . $entri->id)
                        <div class="text-danger text-sm">{{ str_replace('entri data izin.' . $entri->id, $entri->nama, $message) }}</div>
                    @enderror
                </div>
            @else
                <div class="col-md-6 mb-3">
                    <div class="input-group input-group-outline">
                        <label for="{{ $entri->id }}" class="form-label">
                            {{ $entri->nama }}
                            @if ($entri->mandatori)
                                <span class="text-danger ms-1">*</span>
                            @endif
                        </label>
                        <input type="{{ $entri->tipe }}" class="form-control{{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }} @error('entri_data_izin.' . $entri->id)is-invalid @enderror" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}>
                    </div>
                    @error('entri_data_izin.' . $entri->id)
                        <div class="text-danger text-sm">{{ str_replace('entri data izin.' . $entri->id, $entri->nama, $message) }}</div>
                    @enderror
                    @if ($entri->is_multi_value && false)
                        <p class="mb-0 mt-1 text-xs">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Untuk menambahkan beberapa data, pisahkan dengan simbol ";" (contoh: data1;data2;data3)
                        </p>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SEDANG_ENTRI || $edit_entri_data)
        <div class="d-flex align-items-center gap-3 mt-4">
            @if ($edit_entri_data)
                <button class="btn btn-secondary" type="button" wire:click='cancelEditEntriData'>batal</button>
            @else
                <button class="btn btn-secondary" type="reset">reset</button>
            @endif
            <button class="btn bg-gradient-primary" type="submit">simpan</button>
        </div>
    @endif
</form>