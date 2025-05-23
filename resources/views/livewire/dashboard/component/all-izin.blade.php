<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-end gap-3 mb-4">
            <div>
                <label for="start_date" class="form-label mb-0">Dari Tanggal</label>
                <div class="input-group input-group-outline">
                    <input type="date" class="form-control" id="start_date" name="start_date" wire:model.live='start_date'>
                </div>
            </div>
            <div>
                <label for="end_date" class="form-label mb-0">Sampai Tanggal</label>
                <div class="input-group input-group-outline">
                    <input type="date" class="form-control" id="end_date" name="end_date" wire:model.live='end_date'>
                </div>
            </div>
            <div class="input-group input-group-outline">
                <label for="search" class="form-label">cari...</label>
                <input type="text" class="form-control" id="search" name="search" wire:model.live='search'>
            </div>
        </div>
        @foreach ($semua_izin as $izin)
            @php
                $status = $izin->status_izin->last();
                $statusClasses = getStatusIzinClasses($status);

                $current_izin = str_capitalize($izin->pemohon->nama_pemohon, true) . ' (' . str_capitalize(($izin->jenis_perizinan->nama), true) . ')';
            @endphp

            <div class="{{ $loop->iteration == 1 ? 'border-top ' : '' }}border-bottom py-4">
                <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($izin->id)]) }}" class="text-primary text-bold">
                {{ Str::limit($current_izin, 100) }}
                </a>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge badge-dot px-0">
                        <i class="{{ $statusClasses['bgClass'] }}"></i>
                        <span class="text-sm {{ $statusClasses['textClass'] }}" title="{{ $status->progres }}">
                            {{ $status->nama }}
                        </span>
                    </span>
                    <p class="text-xs text-muted mb-0">{{ $izin->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach

        <div class="mt-3">
            {{ $semua_izin->links() }}
        </div>
    </div>
</div>