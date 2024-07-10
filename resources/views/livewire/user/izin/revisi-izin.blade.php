<div class="container">
    <md-linear-progress wire:loading wire:target='save' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    @if ($this->canAccess())
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-10">
                <div class="card">
                    <div class="card-header pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                            <h5 class="text-capitalize mb-0">{{ App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN }}</h5>
                            @if (count($persyaratan) > 0 && !$saved)
                                <button class="btn bg-gradient-primary text-nowrap" wire:click='save'>Simpan</button>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <i class="fa-solid fa-arrow-circle-right"></i>
                            <p class="text-sm mb-0">{{ $status->keterangan }}</p>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <div class="img-preview-wrapper">
                            @foreach ($syarat_izin as $syarat)
                                <div class="img-preview-container ipc-md">
                                    @php($img_preview_url = $persyaratan_tmp[$syarat->id] ?? null)
                                    @php($file_path = $syarat->tipe == 'img' ? $syarat->dokumen_pemohon[0]->nama_file : $syarat->dokumen_pemohon[0]->nama_file_alternatif)
                                    <img src="{{ $img_preview_url ??  route('render-file', ['file_name' => base64_encode($file_path)]) }}" class="img-preview">
                                    <div class="img-preview-content">
                                        <h5 class="img-title">{{ "{$syarat->nama} ({$syarat->tipe})" }}</h5>
                                        <div class="input-group input-group-outline mb-1 flex-column"
                                            x-data="{ uploading: false, progress: 0 }"
                                            x-on:livewire-upload-start="uploading = true"
                                            x-on:livewire-upload-finish="uploading = false"
                                            x-on:livewire-upload-cancel="uploading = false"
                                            x-on:livewire-upload-error="uploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input type="file" class="form-control d-none" id="syarat-{{ $syarat->id }}" wire:model='persyaratan.{{ $syarat->id }}'>
                                            <div x-show="uploading" class="progress my-2 w-50" role="progressbar" aria-label="Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar progress-bar-striped bg-success" x-bind:style="`width: ${progress}%`" style="--bs-progress-bg: var(--color-primary);"></div>
                                            </div>
                                        </div>
                                        @error("persyaratan.{$syarat->id}")
                                            <div class="text-md text-bold text-danger mb-3 text-lowercase">{{ str_replace("persyaratan.{$syarat->id}", $syarat->nama, $message) }}</div>
                                        @enderror
                                        <div class="d-flex justify-content-start align-items-center gap-1 mb-3">
                                            <button class="btn btn-sm bg-gradient-danger" type="button" title="hapus">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                            @if (!isset($persyaratan[$syarat->id]))
                                                <button class="btn btn-sm bg-gradient-info" type="button" title="download">
                                                    <i class="fa-solid fa-download text-sm"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm bg-gradient-info" type="button" title="lihat">
                                                <i class="fa-solid fa-eye text-sm"></i>
                                            </button>
                                            <button class="btn btn-sm bg-gradient-secondary" onclick="document.getElementById('{{ 'syarat-' . $syarat->id }}').click()" type="button" title="ganti">
                                                <i class="fa-solid fa-upload text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="position-absolute inset-0 h-100 w-100 d-none align-items-center justify-content-center bg-dark opacity-75 rounded-3 preview-loading-img" id="loading-{{ $syarat->id }}" data-id="{{ $syarat->id }}">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
            <p>Izin tidak ditemukan!</p>
        </div>
    @endif
    <div class="d-none" id="pdf-container"></div>
    @livewire('dashboard.dashboard-util')
</div>
@push('scripts')
    <script>
        Livewire.on('start-loading', (data) => {
            const loadingId = "loading-" + data[0].id
            loadingEl = document.getElementById(loadingId)
            setTimeout(() => {
                loadingEl.classList.remove('d-none')
                loadingEl.classList.add('d-flex')
                searchIdsToKeep()
            }, 100);
        })
        let loadingIds = []
        function searchIdsToKeep() {
            let keepIdsInterval = setInterval(() => {
                let loadingPreviews = document.querySelectorAll('.preview-loading-img')
                loadingPreviews.forEach(loadingPreview => {
                    if (loadingPreview.classList.contains('d-flex')) {
                        let izin_id = loadingPreview.getAttribute('data-id')
                        let attr_id = loadingPreview.getAttribute('id')
                        if (!loadingIds.includes(attr_id)) {
                            Livewire.dispatch('set-pdf-img', {id: izin_id})
                            loadingIds.push(attr_id)
                        }
                    }
                })
                loadingIds.forEach(loadingId => {
                    loadingEl = document.getElementById(loadingId)
                    if (!loadingEl.classList.contains('d-flex')) {
                        loadingEl.classList.add('d-flex')
                        loadingEl.classList.remove('d-none')
                        clearInterval(keepIdsInterval)
                    }
                })
            }, 500);
        }
        Livewire.on('stop-loading', (data) => {
            const loadingId = "loading-" + data[0].id
            loadingEl = document.getElementById(loadingId)
            setTimeout(() => {
                loadingEl.classList.remove('d-flex')
                loadingEl.classList.add('d-none')
                loadingIds = []
            }, 100);
        })
    </script>
@endpush