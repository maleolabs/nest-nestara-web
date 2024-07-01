<div class="container mb-5">
    <h3>Aktivitas</h3>
    <h6>{{ count($semua_izin) . ' Izin' }}</h6>
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
                        <div class="d-flex align-items-top gap-2 my-3">
                            @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                <i class="fa-regular fa-circle-check mt-1 text-success"></i>
                            @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED)
                                <i class="fa-solid fa-circle-minus mt-1 text-muted"></i>
                            @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                <i class="fa-solid fa-hourglass-half mt-1 text-dark"></i>
                            @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                <i class="fa-solid fa-circle-xmark mt-1 text-danger"></i>
                            @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                <i class="fa-solid fa-triangle-exclamation mt-1 text-warning"></i>
                            @endif
                            <div>
                                <p class="mb-0 @if ($status->progres == App\utils\enums\StatusIzinProgressEnum::FINISHED)
                                        text-success
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::UNPROCESSED)
                                        text-muted
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::PROCESSING)
                                        text-dark
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::CANCELED)
                                        text-danger
                                    @elseif ($status->progres == App\utils\enums\StatusIzinProgressEnum::ERROR)
                                        text-warning
                                    @endif">
                                    {{ $status->nama != App\utils\enums\StatusIzinEnum::DIDAFTARKAN ? "{$status->nama} ({$status->progres})" : $status->nama }}
                                </p>
                                <p class="mb-0">{{ $status->created_at->translatedFormat('l, d F Y H:i') . ' WITA' }}</p>
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
                                        <i class="fa-solid fa-circle-minus me-1"></i>
                                        Batalkan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
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