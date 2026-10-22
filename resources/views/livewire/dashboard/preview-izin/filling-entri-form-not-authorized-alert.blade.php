<div class="min-h-[30vh] relative">
    <div class="absolute z-1 inset-0 w-full h-full p-4 min-h-[30vh] overflow-hidden">
        <h5 class="mb-1 font-bold">
            <i class="fa-regular fa-circle-right mr-1"></i>
            Entri Data
        </h5>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @foreach ($entri_data as $entri)
                @if ($entri->tipe == 'textarea')
            <div class="mb-3 min-w-0">
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
            <div class="mb-3 min-w-0">
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
    <div class="absolute inset-0 w-full h-full z-2 flex flex-col gap-2 items-center justify-center bg-gradient-danger-up-bottom rounded-xl">
            <i class="fa-solid fa-xmark-circle text-xl text-red-600"></i>
        <p class="text-center font-bold text-red-600">
            Permohonan izin ini sedang dientri oleh pegawai lain. Anda tidak dapat mengisi formulir ini!
        </p>
    </div>
</div>
