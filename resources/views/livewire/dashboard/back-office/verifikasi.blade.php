<div class="card">
    @if ($preview_izin)
        <div class="card-header border-bottom">
            <h6 class="mb-0">Verifikasi</h6>
        </div>
        <div class="card-body">
            <h5>{{ $preview_izin->jenis_perizinan->nama }}</h5>
            <button class="btn bg-gradient-primary" type="button" wire:click='verifikasi'>verifikasi</button>
        </div>
    @endif
</div>