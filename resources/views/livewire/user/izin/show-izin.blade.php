<div class="container mb-5">
    @if ($to_confirm_delete)
        <div class="row justify-content-center">
            <div class="col-md-5 col-sm-7">
                <div class="card p-4 mb-0">
                    <h5 class="mb-0">Konfirmasi Penghapusan Permohonan Izin</h5>
                    <div class="rounded-3 mt-3 p-3" style="border: 3px dotted #DEE2E6">
                        <p class="text-sm text-align-justify">
                            Apakah Anda yakin ingin menghapus permohonan izin ini? Harap diperhatikan bahwa tindakan ini bersifat permanen dan tidak dapat dibatalkan. Semua data terkait permohonan izin ini akan dihapus secara permanen. Pastikan Anda telah mempertimbangkan keputusan ini dengan matang sebelum melanjutkan.
                        </p>
                        <div class="form-check ms-0 ps-0">
                            <input type="checkbox" wire:model.live='confirm_delete' class="form-check-input" id="confirm" name="confirm">
                            <label for="confirm" class="form-check-label">konfirmasi penghapusan</label>
                        </div>
                        <button class="btn bg-gradient-danger mt-3 w-100{{ !$confirm_delete ? ' disabled' : '' }}" type="button" {{ !$confirm_delete ? 'disabled' : '' }} wire:click='confirmDelete'>
                            <span wire:loading.remove wire:target='confirmDelete'>Hapus</span>
                            <div class="spinner-border spinner-border-sm" role="status" wire:loading wire:target='confirmDelete'>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>    
    @else    
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>Aktivitas</h3>
                <h6>{{ count($semua_izin) . ' Izin' }}</h6>
            </div>
            <a href="{{ route('create-izin') }}" class="btn bg-gradient-primary">buat izin baru</a>
        </div>
        <div class="row">
            @foreach ($semua_izin->reverse() as $izin)
                <div class="col-12 mb-3">
                    @php
                        $is_izin_new = $izin->created_at >= \Carbon\Carbon::now()->subMinutes(30);
                    @endphp
                    <div class="card flex-row align-items-top p-4 mb-0{{ $is_izin_new ? ' bg-gradient-primary' : '' }}">
                        <div>
                            <a href="javascript:;" id="modalRightShowBtn" wire:click='preview({{ $izin->id }})'>
                                <h6 class="border-bottom border-2 d-inline border-dark">{{ $izin->jenis_perizinan->nama }}</h6>
                                @if ($is_izin_new)
                                    <span class="badge bg-gradient-info text-xs px-2 py-1 text-white text-capitalize">baru</span>
                                @endif
                            </a>
                            @php
                                $status = $izin->status_izin->last()
                            @endphp
                            {!! renderStatusIzinWithIcon($status) !!}
                        </div>
                        @if ($this->isIzinCancelable($status->nama))
                            <div class="ms-auto mb-auto">
                                <div class="dropdown">
                                    <button class="btn p-0 card-icon rounded-circle" data-bs-toggle="dropdown" id="cancelIzinAction">
                                        <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="cancelIzinAction">
                                        <li>
                                            <a class="dropdown-item" href="javascript:;" wire:click='cancel({{ $izin->id }})'>
                                                <i class="fa-solid fa-circle-minus me-1"></i>
                                                Batalkan
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            {{ $semua_izin->links() }}
        </div>
        @include('livewire.helper.modal-right')
    @endif
</div>
@push('scripts')
    <script>
        Livewire.hook('morph.updated', ({el, component}) => {
            if ('selectedPreview' in component.snapshot.data && component.snapshot.data['selectedPreview'] !== null) {
                document.querySelector('.modal-right').classList.remove('d-none')
                document.querySelector('.modal-right-content').style.right = '0'
            }
        })
    </script>
@endpush