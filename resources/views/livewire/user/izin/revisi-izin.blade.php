<div class="container">
    @if ($this->canAccess())
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-10">
                <div class="card mb-0">
                    <div class="card-header pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                            <h5 class="text-capitalize mb-0">{{ App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN }}</h5>
                            @if (count($persyaratan) > 0)
                                <button class="btn bg-gradient-primary text-nowrap">Simpan</button>
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
                                    @php($img_preview_url = ($syarat->tipe == 'img' && isset($persyaratan[$syarat->id])) ? $persyaratan[$syarat->id]->temporaryUrl() : null)
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
                                            <div class="text-sm text-danger mt-1 mb-3">{{ str_capitalize($message, true) }}</div>
                                        @enderror
                                        <div class="d-flex justify-content-start align-items-center gap-1 mb-3">
                                            <button class="btn btn-sm bg-gradient-danger" type="button" title="hapus">
                                                <i class="fa-solid fa-trash text-sm"></i>
                                            </button>
                                            @if (!isset($peryaratan[$syarat->id]))
                                                <button class="btn btn-sm bg-gradient-info" type="button" title="download">
                                                    <i class="fa-solid fa-download text-sm"></i>
                                                </button>
                                                <button class="btn btn-sm bg-gradient-info" type="button" title="lihat">
                                                    <i class="fa-solid fa-eye text-sm"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm bg-gradient-secondary" onclick="document.getElementById('{{ 'syarat-' . $syarat->id }}').click()" type="button" title="ganti">
                                                <i class="fa-solid fa-upload text-sm"></i>
                                            </button>
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
</div>
