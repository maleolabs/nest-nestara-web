@push('breadcrumb-item')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.jenis-perizinan.index') }}">Jenis Perizinan</a>
    </li>
    <li class="breadcrumb-item active">
        {{ Str::limit(str_capitalize($preview_jenis->nama, true), 70) }}
    </li>
@endpush
<div class="row">
    <md-linear-progress wire:loading wire:target='selectTab' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

    <div class="col-3 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action cursor-pointer text-sm fw-medium{{ $current_tab == self::DETAIL_TAB ? ' active' : '' }}" wire:click="selectTab('{{ self::DETAIL_TAB }}')">
                        Detail Perizinan
                    </li>
                    <li class="list-group-item list-group-item-action cursor-pointer text-sm fw-medium{{ $current_tab == self::PERSYARATAN_TAB ? ' active' : '' }}" wire:click="selectTab('{{ self::PERSYARATAN_TAB }}')">
                        Persyaratan
                    </li>
                    <li class="list-group-item list-group-item-action cursor-pointer text-sm fw-medium{{ $current_tab == self::DATA_ENTRI_TAB ? ' active' : '' }}" wire:click="selectTab('{{ self::DATA_ENTRI_TAB }}')">
                        Properti Data Entri
                    </li>
                    <li class="list-group-item list-group-item-action cursor-pointer text-sm fw-medium{{ $current_tab == self::PENGATURAN_SURAT_TAB ? ' active' : '' }}" wire:click="selectTab('{{ self::PENGATURAN_SURAT_TAB }}')">
                        Pengaturan Surat
                    </li>
                    <li class="list-group-item list-group-item-action cursor-pointer text-sm fw-medium{{ $current_tab == self::TINDAKAN_TAB ? ' active' : '' }}" wire:click="selectTab('{{ self::TINDAKAN_TAB }}')">
                        Tindakan Perizinan
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-9 d-flex align-items-stretch">
        <div class="card w-100 min-vh-50">
            <div class="card-body">
                @switch($current_tab)
                    @case(self::DETAIL_TAB)
                        @livewire('admin.jenis-perizinan.detail-perizinan', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::PERSYARATAN_TAB)
                        @livewire('admin.jenis-perizinan.persyaratan-izin', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::DATA_ENTRI_TAB)
                        @livewire('admin.jenis-perizinan.properti-data-entri', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::PENGATURAN_SURAT_TAB)
                        @livewire('admin.jenis-perizinan.pengaturan-surat', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @case(self::TINDAKAN_TAB)
                        @livewire('admin.jenis-perizinan.tindakan-perizinan', [
                            'preview_id' => $preview_id, 
                            'preview_jenis' => $preview_jenis
                        ])
                        @break
                    @default
                        
                @endswitch      
                
            </div>
        </div>
    </div>
</div>