<div>
    <div class="nav-divider mt-3 mb-2">Semua Permohonan Izin</div>
    @foreach ($all_izin as $izin)
        <li class="nav-item">
            <a href="{{ route('preview-izin', ['izin_id' => base64_encode($izin->id)]) }}" class="nav-link">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <span class="text-sm text-bold d-block">{{ Str::limit(str_capitalize($izin->jenis_perizinan->nama, true), 30) }}</span>
                        <small class="text-xs d-block">{{ Str::limit($izin->pemohon->nama_pemohon, 30) }}</small>
                        <small class="text-xs text-muted">{{ $izin->created_at->diffForHumans() }}</small>
                    </div>
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </div>
            </a>
        </li>
    @endforeach
</div>