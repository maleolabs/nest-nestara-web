<section class="w-full rounded-2xl border border-ink-200 bg-white p-4 shadow-sm sm:p-5" aria-labelledby="pendataan-filter-title">
    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 id="pendataan-filter-title" class="text-base font-bold text-ink-900">Daftar permohonan</h2>
        <p class="text-sm text-ink-500">Hanya permohonan siap rekomendasi yang tampil. Pilih baris untuk membuka detail.</p>
        </div>
        <span class="text-xs text-ink-500" wire:loading wire:target="start_date, end_date, search">Memuat data…</span>
    </div>
    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div>
            <label for="start_date" class="mb-1 block text-sm font-semibold text-ink-700">Dari Tanggal</label>
            <div class="space-y-1">
                <input type="date" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="start_date" name="start_date" wire:model.live='start_date'>
            </div>
        </div>
        <div>
            <label for="end_date" class="mb-1 block text-sm font-semibold text-ink-700">Sampai Tanggal</label>
            <div class="space-y-1">
                <input type="date" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="end_date" name="end_date" wire:model.live='end_date'>
            </div>
        </div>
        <div><label for="search" class="mb-1 block text-sm font-semibold text-ink-700">Cari permohonan</label><input type="search" autocomplete="off" placeholder="Nomor, nama, atau ID" class="w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" id="search" name="search" wire:model.live='search'></div>
    </div>

    <div class="overflow-x-auto relative">
        <table class="w-full min-w-[760px] text-left">
            <thead class="border-b border-ink-200 bg-ink-50">
                <tr>
                    <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">No</th>
                    <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">No Pendaftraran</th>
                    <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">ID Pemohon</th>
                    <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">Pemohon</th>
                    <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">Jenis Izin</th>
                    <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">Tanggal Permohonan</th>
                    @if (isset($table_content))
                        <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">{{ $table_content }}</th>
                    @endif
                    <th class="uppercase text-ink-500 text-[10px] font-bold opacity-7">Status siap</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($all_izin as $izin)
                    @php
                        $status = isset($table_content) && $this->getStatusCetak($izin) ? $this->getStatusCetak($izin) : $izin->status_izin->last();
                        $statusClasses = getStatusIzinProcessClasses($status);
                        $statusKeterangan = $status?->keterangan ?? 'Belum ada status';
                    @endphp
                    <tr tabindex="0" role="button" aria-label="Buka permohonan {{ $izin->no_pendaftaran }}" class="cursor-pointer data-izin focus-within:bg-nest-50 hover:bg-ink-50 focus:bg-nest-50" data-izin-id="{{ $izin->id }}" wire:click='previewDataIzin({{ $izin->id }})' @keydown.enter="this.click()" @keydown.space.prevent="this.click()">
                        <td class="align-middle text-center text-sm">{{ $loop->iteration }}</td>
                        <td class="text-sm">
                            <p class="text-xs font-bold">{{ $izin->no_pendaftaran }}</p>
                        </td>
                        <td class="text-sm">
                            <p class="text-xs font-bold">{{ $izin->pemohon->nomor_id }}</p>
                        </td>
                        <td>
                            <h6 class="mb-0 text-sm text-wrap">
                                {{ str_capitalize($izin->pemohon->nama_pemohon, true) }}
                            </h6>
                        </td>
                        <td>
                            <p class="text-sm text-ink-500 text-wrap">
                                {{ str_capitalize($izin->jenis_perizinan->nama, true) }}
                            </p>
                        </td>
                        <td class="text-center text-sm">
                            {{ $this->getTanggalPermohonan($izin->id) }}
                        </td>
                        @if (isset($table_content) && isset($izin->no_surat))
                            <td class="text-sm">
                                {{ $izin->no_surat }}
                            </td>
                        @endif
                        <td class="align-middle">
                            <div class="flex flex-col items-center justify-center">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold badge-dot">
                                    <i class="{{ $statusClasses['bgClass'] }}"></i>
                                    @php
                                         $keterangan = $statusKeterangan;
                                        $statusEnum = App\utils\enums\BackOfficeStatusEnum::class;

                                        if (in_array($keterangan, [$statusEnum::DICETAK, $statusEnum::IZIN_AKAN_DITERBITKAN])) {
                                            $count = getCountDownloadedSurat($izin->id);
                                            $keterangan = $keterangan == $statusEnum::IZIN_AKAN_DITERBITKAN ? 'dicetak' : $keterangan;
                                            $keterangan .= ' ' . $count;
                                        }
                                    @endphp
                                    <span class="text-sm {{ $statusClasses['textClass'] }}">
                                        {{ Str::limit($keterangan, 16) }}
                                    </span>
                                </span>
                                 @if ($status?->updated_at)<p class="text-xs text-ink-500">{{ $status->updated_at->diffForHumans() }}</p>@endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ isset($table_content) ? 8 : 7 }}">
                            <div class="w-full flex items-center justify-center gap-2 min-h-[30vh]">
                                <div class="text-center"><i class="fa-regular fa-folder-open text-xl text-ink-400" aria-hidden="true"></i><p class="font-semibold text-ink-700">Tidak ada data permohonan</p><p class="text-sm text-ink-500">Ubah rentang tanggal atau kata kunci pencarian.</p></div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div wire:loading wire:target='previewDataIzin, start_date, end_date, search'>
            <div class="absolute inset-0 flex items-center justify-center bg-white/75" aria-live="polite" aria-label="Memuat data">
                <span class="rounded-full bg-white px-3 py-2 text-sm font-semibold text-ink-700 shadow-sm">Memuat antrean…</span>
            </div>
        </div>
    </div>
</section>
