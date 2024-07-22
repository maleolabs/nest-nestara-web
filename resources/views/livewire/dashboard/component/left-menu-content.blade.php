<div>
    <div class="nav-divider mt-3 mb-2">Permohonan Izin</div>
    @foreach ($all_izin as $izin)
        <li class="nav-item">

            <a href="{{ route('preview-izin', ['izin_id' => $this->encryptId($izin->id)]) }}" class="nav-link">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <span class="text-sm text-bold d-block">{{ Str::limit(str_capitalize($izin->jenis_perizinan->nama, true), 30) }}</span>
                        <small class="text-xs d-block">{{ Str::limit($izin->pemohon->nama_pemohon, 30) }}</small>
                        {!! renderStatusIzinWithIcon($izin->status_izin->last(), '', true, true) !!}
                        <small class="text-xs text-muted d-block ps-3">{{ $izin->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </a>
        </li>
    @endforeach
    <li class="nav-item px-3">
        <a href="{{ 'izin.index' }}" class="btn btn-light btn-sm text-capitalize w-100">Lihat semua</a>
    </li>
</div>