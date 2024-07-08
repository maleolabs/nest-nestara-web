<div class="container mb-5">
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
                <div class="card flex-row align-items-top p-4 mb-0{{ $loop->index == 0 ? ' bg-gradient-primary' : '' }}">
                    <div>
                        <a href="javascript:;" id="modalRightShowBtn" wire:click='preview({{ $izin->id }})'>
                            <h6 class="border-bottom border-2 d-inline border-dark">{{ $izin->jenis_perizinan->nama }}</h6>
                            @if ($loop->index == 0)
                                <span class="badge bg-gradient-info text-xs px-2 py-1 text-white text-capitalize">baru</span>
                            @endif
                        </a>
                        @php
                            $status = $izin->status_izin->last()
                        @endphp
                        {!! renderStatusIzinWithIcon($status) !!}
                    </div>
                    @if ($this->isIzinCancelable($izin->status_izin->last()->nama))
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