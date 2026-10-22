<div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
    <div class="p-4">
        <div class="flex items-end gap-3 mb-4">
            <div>
                <label for="start_date" class="mb-1 block text-sm font-semibold text-ink-700 mb-0">Dari Tanggal</label>
                <div class="space-y-1">
                    <input type="date" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="start_date" name="start_date" wire:model.live='start_date'>
                </div>
            </div>
            <div>
                <label for="end_date" class="mb-1 block text-sm font-semibold text-ink-700 mb-0">Sampai Tanggal</label>
                <div class="space-y-1">
                    <input type="date" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="end_date" name="end_date" wire:model.live='end_date'>
                </div>
            </div>
            <div><label for="search" class="block text-xs font-bold tracking-wide text-ink-700 uppercase">cari...</label><input type="text" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 " id="search" name="search" wire:model.live='search'></div>
        </div>
        @foreach ($semua_izin as $izin)
            @php
                $status = $izin->status_izin->last();
                $statusClasses = getStatusIzinClasses($status);

                $current_izin = str_capitalize($izin->pemohon->nama_pemohon, true) . ' (' . str_capitalize(($izin->jenis_perizinan->nama), true) . ')';
            @endphp

            <div class="{{ $loop->iteration == 1 ? 'border-top ' : '' }}border-b py-4">
                <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($izin->id)]) }}" class="text-primary font-bold">
                {{ Str::limit($current_izin, 100) }}
                </a>
                <div class="flex gap-2 items-center">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold badge-dot px-0">
                        <i class="{{ $statusClasses['bgClass'] }}"></i>
                        <span class="text-sm {{ $statusClasses['textClass'] }}" title="{{ $status->progres }}">
                            {{ $status->nama }}
                        </span>
                    </span>
                    <p class="text-xs text-ink-500 mb-0">{{ $izin->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach

        <div class="mt-3">
            {{ $semua_izin->links() }}
        </div>
    </div>
</div>