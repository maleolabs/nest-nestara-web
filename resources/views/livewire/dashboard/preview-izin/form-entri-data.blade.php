<form wire:submit.prevent='saveEntri'>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        @foreach ($entri_data as $entri)
            @if ($entri->tipe == 'textarea')
                <div class="{{ $loop->iteration == 1 || $loop->iteration == count($entri_data) ? 'min-w-0' : 'min-w-0' }} mb-3">
                    <div class="space-y-1">
                        <label for="{{ $entri->id }}" class="mb-1 block text-sm font-semibold text-ink-700">
                            {{ $entri->nama }}
                            @if ($entri->mandatori)
                                <span class="text-red-600 ml-1">*</span>
                            @endif
                        </label>
                        <textarea name="{{ $entri->id }}" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }} @error('entri_data_izin.' . $entri->id)is-invalid @enderror" id="{{ $entri->id }}" rows="3" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}></textarea>
                    </div>
                    @error('entri_data_izin.' . $entri->id)
                        <div class="text-red-600 text-sm">{{ str_replace('entri data izin.' . $entri->id, $entri->nama, $message) }}</div>
                    @enderror
                </div>
            @else
                <div class="min-w-0 mb-3">
                    <div class=" relative">
                        <label for="{{ $entri->id }}" class="mb-1 block text-sm font-semibold text-ink-700">
                            {{ $entri->nama }}
                            @if ($entri->mandatori)
                                <span class="text-red-600 ml-1">*</span>
                            @endif
                        </label>
                        <input type="{{ $entri->tipe }}" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100{{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }} @error('entri_data_izin.' . $entri->id)is-invalid @enderror" wire:model='entri_data_izin.{{ $entri->id }}' {{ $last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && !$edit_entri_data ? ' disabled' : '' }}>
                        
                        <div class="absolute top-full z-10 hidden w-full bg-white shadow-lg input-group-placeholder">
                            <ul class="divide-y divide-ink-100 rounded-xl border border-ink-200 bg-white shadow-lg">
                                @foreach ($entri_data_placeholder as $key => $placeholder)
                                    <li class="cursor-pointer px-3 py-2 hover:bg-ink-50" wire:click="setEntriValue({{ $entri->id }}, '{{ $key }}')">
                                        <span class="text-xs block">{{ $key }}</span>
                                        {{ $placeholder }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @error('entri_data_izin.' . $entri->id)
                        <div class="text-red-600 text-sm">{{ str_replace('entri data izin.' . $entri->id, $entri->nama, $message) }}</div>
                    @enderror
                    @if ($entri->is_multi_value && false)
                        <p class=" mt-1 text-xs">
                            <i class="fa-solid fa-info-circle mr-1"></i>
                            Untuk menambahkan beberapa data, pisahkan dengan simbol ";" (contoh: data1;data2;data3)
                        </p>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SEDANG_ENTRI || $edit_entri_data)
        <div class="flex items-center gap-3 mt-4">
            @if ($edit_entri_data)
                <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-ink-100 text-ink-800 hover:bg-ink-200" type="button" wire:click='cancelEditEntriData'>batal</button>
            @else
                <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-ink-100 text-ink-800 hover:bg-ink-200" type="reset">reset</button>
            @endif
            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white" type="submit">simpan</button>
        </div>
    @endif
</form>
