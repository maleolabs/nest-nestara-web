@push('breadcrumb-item')
    <li class="breadcrumb-item active">
        Pegawai
    </li>
@endpush
<div class="row">
    <md-linear-progress wire:loading wire:target='selectTab' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="col-3 d-flex align-items-stretch">
        <div class="card w-100 min-vh-50">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::FRONT_OFFICE_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::FRONT_OFFICE_TAB }}')" onclick="switchTab('{{ self::FRONT_OFFICE_TAB }}')">
                        front office
                    </li>
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::BACK_OFFICE_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::BACK_OFFICE_TAB }}')" onclick="switchTab('{{ self::BACK_OFFICE_TAB }}')">
                        back office
                    </li>
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::PENANDATANGAN_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::PENANDATANGAN_TAB }}')" onclick="switchTab('{{ self::PENANDATANGAN_TAB }}')">
                        penandatangan
                    </li>
                    <li class="list-group-item list-group-item-action btn-edit text-sm fw-medium text-capitalize {{ $current_tab == self::NEW_PEGAWAI_TAB ? 'active' : '' }}" wire:click="selectTab('{{ self::NEW_PEGAWAI_TAB }}')" onclick="switchTab('{{ self::NEW_PEGAWAI_TAB }}')">
                        buat pegawai baru
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-9 d-flex align-items-stretch">
        <div class="card w-100 position-relative">
            <div class="card-body">
                @if ($to_detail)
                    @include('livewire.admin.partials.pegawai-detail')
                @else
                    @if (in_array($current_tab, [self::FRONT_OFFICE_TAB, self::BACK_OFFICE_TAB]))
                        <h5 class="fw-semibold">
                            Pegawai {{ str_capitalize(str_replace('-', ' ', $current_tab), true) }}
                        </h5>
                        <hr>
                        <div class="row">
                            @foreach ($users as $user)
                                <div class="col-4">
                                    <div class="dash-card-mono p-0 border">
                                        <img src="{{ asset('storage/users/' . $user->foto) }}" alt="{{ $user->name }}" class="card-mono-img">
                                        <div class="p-3">
                                            <h6 class="fw-medium btn-edit text-decoration-underline" wire:click='userDetail({{ $user->id }})'>
                                                {{ $user->pegawai->nama }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($current_tab == self::PENANDATANGAN_TAB)
                    @elseif ($current_tab == self::NEW_PEGAWAI_TAB)
                        
                    @endif
                @endif
            </div>

            <div wire:loading wire:target='userDetail'>
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-75 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function switchTab(tab) {
            let url = new URL(window.location)
            url.searchParams.set('tab', tab)
            window.history.pushState({}, '', url)
        }
    </script>
@endpush