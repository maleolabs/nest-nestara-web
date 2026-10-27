<div>
    <div class="mb-3 flex items-center justify-between px-3">
        <p class="text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Permohonan Izin</p>
        <span class="text-[11px] font-semibold tabular-nums text-ink-400">{{ count($all_izin) }}</span>
    </div>
    @if (count($all_izin) === 0)
        <p class="rounded-xl border border-dashed border-ink-200 bg-ink-50/50 px-3 py-4 text-center text-xs text-ink-500">Belum ada permohonan izin.</p>
    @endif
    @foreach ($all_izin as $izin)
        <li class="mb-1 list-none">

            <a href="{{ route('preview-izin', ['izin_id' => $this->encryptId($izin->id)]) }}" class="block rounded-xl border border-transparent px-3 py-2.5 transition hover:border-ink-200 hover:bg-ink-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-nest-700">
                <div class="flex justify-between items-center w-full">
                    <div>
                        <span class="block truncate text-sm font-bold text-ink-800">{{ Str::limit(str_capitalize($izin->jenis_perizinan->nama, true), 30) }}</span>
                        <small class="block truncate text-xs text-ink-500">{{ Str::limit($izin->pemohon->nama_pemohon, 30) }}</small>
                        {!! renderStatusIzinWithIcon($izin->status_izin->last(), '', true, true) !!}
                        <small class="text-xs text-ink-500 block ps-3">{{ $izin->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </a>
        </li>
    @endforeach
    <li class="list-none px-3 pt-2">
        <a href="{{ route('izin.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-ink-200 bg-white px-3 py-2 text-xs font-semibold capitalize text-ink-800 transition hover:bg-ink-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-nest-700">Lihat semua</a>
    </li>
</div>
