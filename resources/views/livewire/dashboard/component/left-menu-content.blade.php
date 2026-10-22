<div>
    <div class="nav-divider mt-3 mb-2">Permohonan Izin</div>
    @foreach ($all_izin as $izin)
        <li class="nav-item">

            <a href="{{ route('preview-izin', ['izin_id' => $this->encryptId($izin->id)]) }}" class="nav-link">
                <div class="flex justify-between items-center w-full">
                    <div>
                        <span class="text-sm font-bold block">{{ Str::limit(str_capitalize($izin->jenis_perizinan->nama, true), 30) }}</span>
                        <small class="text-xs block">{{ Str::limit($izin->pemohon->nama_pemohon, 30) }}</small>
                        {!! renderStatusIzinWithIcon($izin->status_izin->last(), '', true, true) !!}
                        <small class="text-xs text-ink-500 block ps-3">{{ $izin->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </a>
        </li>
    @endforeach
    <li class="nav-item px-3">
        <a href="{{ route('izin.index') }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition border border-ink-200 bg-white text-ink-800 hover:bg-ink-50 px-3 py-1.5 text-xs capitalize w-full">Lihat semua</a>
    </li>
</div>