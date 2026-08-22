<div class="table-responsive relative">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-ink-200 bg-ink-50/60">
                @if ($menu == self::DATA_PEMOHON)
                    <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">No</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">ID</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Nama</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Alamat</th>
                @elseif ($menu == self::DATA_IZIN_BARU)
                    <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">No</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">No Pendaftaran</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">ID Pemohon</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Pemohon</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Jenis Izin</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Tanggal</th>
                    <th class="px-4 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Status</th>
                    <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Peninjau</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-100">
            @if ($menu == self::DATA_PEMOHON)
                @forelse ($semua_pemohon as $pemohon)
                    <tr class="cursor-pointer data-pemohon transition hover:bg-nest-50/60" data-pemohon-id="{{ $pemohon->id }}" wire:click='previewPemohon({{ $pemohon->id }})'>
                        <td class="px-5 py-3.5 text-sm font-semibold text-ink-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3.5 text-sm font-bold text-ink-900">{{ $pemohon->nomor_id }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ asset('storage/users/' . ($pemohon->user ? $pemohon->user->foto : 'profile.png')) }}" alt="{{ $pemohon->nama_pemohon }}" class="h-8 w-8 rounded-full object-cover ring-1 ring-ink-200">
                                <span class="text-sm font-bold text-ink-900">{{ str_capitalize($pemohon->nama_pemohon, true) }}</span>
                            </div>
                        </td>
                        <td class="max-w-[280px] px-4 py-3.5 text-sm capitalize text-ink-600">
                            <span class="line-clamp-2">{{ $this->getAlamat($pemohon) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5">
                            <div class="flex flex-col items-center justify-center gap-2 py-14 text-center">
                                <div class="h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-inbox"></i></div>
                                <p class="text-sm font-bold text-ink-900">{{ $search != '' ? 'Tidak ditemukan' : 'Belum ada data pemohon' }}</p>
                                @if ($search != '')<p class="text-sm text-ink-500">Hasil untuk “{{ $search }}” tidak ada.</p>@endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            @elseif ($menu == self::DATA_IZIN_BARU)
                @forelse ($semua_izin as $izin)
                    @php
                        $status = $izin->latestStatus;
                        $statusClasses = getStatusIzinClasses($status)
                    @endphp
                    <tr class="cursor-pointer data-izin transition hover:bg-nest-50/60" data-izin-id="{{ $izin->id }}" wire:click='previewDataIzin({{ $izin->id }})'>
                        <td class="px-5 py-3.5 text-sm font-semibold text-ink-400">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3.5 text-sm font-bold text-ink-900">{{ $izin->no_pendaftaran }}</td>
                        <td class="px-4 py-3.5 text-sm font-semibold text-ink-700">{{ $izin->pemohon->nomor_id }}</td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ asset('storage/users/' . ($izin->pemohon->user ? $izin->pemohon->user->foto : 'profile.png')) }}" alt="{{ $izin->pemohon->nama_pemohon }}" class="h-8 w-8 rounded-full object-cover ring-1 ring-ink-200">
                                <span class="text-sm font-bold text-ink-900">{{ str_capitalize($izin->pemohon->nama_pemohon, true) }}</span>
                            </div>
                        </td>
                        <td class="max-w-[220px] px-4 py-3.5 text-sm text-ink-600"><span class="line-clamp-2">{{ str_capitalize($izin->jenis_perizinan->nama, true) }}</span></td>
                        <td class="whitespace-nowrap px-4 py-3.5 text-sm text-ink-600">{{ $this->getTanggalPermohonan($izin) }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-bold ring-1 ring-ink-200 {{ $statusClasses['textClass'] }}">
                                <i class="{{ $statusClasses['bgClass'] }} h-1.5 w-1.5 rounded-full"></i> {{ Str::limit($status->nama, 18) }}
                            </span>
                            <p class="mt-0.5 text-[11px] text-ink-400">{{ $status->updated_at->diffForHumans() }}</p>
                        </td>
                        <td class="max-w-[160px] px-4 py-3.5 text-center text-sm font-semibold {{ $status->pegawai ? 'text-ink-800' : 'text-ink-400 italic' }}">
                            {{ $status->pegawai ? $status->pegawai->nama : 'Belum ditinjau' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5">
                            <div class="flex flex-col items-center justify-center gap-2 py-14 text-center">
                                <div class="h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-inbox"></i></div>
                                <p class="text-sm font-bold text-ink-900">Tidak ada data</p>
                                <p class="text-sm text-ink-500">Coba ubah filter tanggal atau kata kunci pencarian.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>
    <div wire:loading wire:target='previewDataIzin, previewPemohon, start_date, end_date, search'>
        <div class="absolute inset-0 z-10 grid place-items-center bg-white/75 backdrop-blur-[2px]">
            <div class="flex flex-col items-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-xl text-nest-700"></i>
                <p class="text-xs font-bold text-ink-500">Memuat…</p>
            </div>
        </div>
    </div>

    @if ($menu == self::DATA_PEMOHON)
        <x-pagination-nestara :paginator="$semua_pemohon" />
    @else
        <x-pagination-nestara :paginator="$semua_izin" />
    @endif
</div>
