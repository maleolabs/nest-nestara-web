<div class="container mb-5">
    <h3>Aktivitas</h3>
    <h6>{{ count($semua_izin) . ' Izin' }}</h6>
    <div class="row">
        @foreach ($semua_izin->reverse() as $izin)
            <div class="col-12 mb-3">
                <div class="card flex-row align-items-top p-4 mb-0{{ $loop->index == 0 ? ' bg-gradient-primary' : '' }}">
                    <div>
                        <a href="javascript:;" id="modalRightShowBtn" wire:click='preview({{ $izin->jenis_perizinan->id }})'>
                            <h6 class="border-bottom border-2 d-inline border-dark">{{ $izin->jenis_perizinan->nama }}</h6>
                            @if ($loop->index == 0)
                                <span class="badge bg-gradient-info text-xs px-2 py-1 text-white text-capitalize">baru</span>
                            @endif
                        </a>
                        <div class="d-flex align-items-top gap-2">
                            <i class="fa-regular fa-circle-check mt-1 text-success"></i>
                            <div>
                                <p class="mb-0 text-success">Telah didaftarkan</p>
                                <p class="mb-0">{{ $izin->created_at->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="ms-auto mb-auto">
                        <div class="dropdown">
                            <button class="btn p-0 card-icon rounded-circle" data-bs-toggle="dropdown" id="dataIzinAction">
                                <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dataIzinAction">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        Hapus
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @include('livewire.helper.modal-right')
</div>
@push('scripts')
    <script>
        Livewire.hook('morph.updated', ({el, component}) => {
            if ('selectedPreview' in component.snapshot.data) {
                document.querySelector('.modal-right').classList.remove('d-none')
                document.querySelector('.modal-right-content').style.right = '0'
            }
        })
    </script>
@endpush