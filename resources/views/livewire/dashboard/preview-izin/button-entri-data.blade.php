<div class="min-h-[30vh] relative">
    <div class="absolute z-1 inset-0 w-full h-full p-4 min-h-[30vh] overflow-hidden">
        <h5 class="font-bold mb-1">
            <i class="fa-regular fa-circle-right mr-1"></i>
            Entri Data
        </h5>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($entri_data as $entri)
                @if ($entri->tipe == 'textarea')
                    <div class="min-w-0 mb-3">
                        <div class="space-y-1">
                            <label class="mb-1 block text-sm font-semibold text-ink-700">
                                {{ $entri->nama }}
                                @if ($entri->mandatori)
                                    <span class="text-red-600 ml-1">*</span>
                                @endif
                            </label>
                            <textarea class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100"></textarea>
                        </div>
                    </div>
                @else
                    <div class="min-w-0 mb-3">
                        <div class="space-y-1">
                            <label class="mb-1 block text-sm font-semibold text-ink-700">
                                {{ $entri->nama }}
                                @if ($entri->mandatori)
                                    <span class="text-red-600 ml-1">*</span>
                                @endif
                            </label>
                            <input type="{{ $entri->tipe }}" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="absolute inset-0 w-full h-full z-2 flex flex-col gap-2 items-center justify-center bg-gradient-light-up-bottom rounded-xl">
        @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::PERUBAHAN_ENTRI && !$preview_izin->ambil_entri_lama)
            <p class=" font-bold">{{ $last_izin_status->keterangan }}</p>
            <div class="flex justify-center gap-3 items-center">
                <a href="{{ route('pegawai.pendataan') }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-sky-600 text-white flex items-center">
                    entri data kembali
                </a>
                <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-amber-500 text-white flex items-center" type="button" wire:click='keepOldEntri'>
                    tetap gunakan entri yang lama
                    <div class="animate-spin h-4 w-4 rounded-full border-2 border-current border-t-transparent ml-2" role="status" wire:loading wire:target='keepOldEntri'>
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            </div>
        @else
            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white flex items-center" type="button" wire:click='entriData'>
                entri data
                <div class="animate-spin h-4 w-4 rounded-full border-2 border-current border-t-transparent ml-2" role="status" wire:loading wire:target='entriData'>
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        @endif
    </div>
</div>