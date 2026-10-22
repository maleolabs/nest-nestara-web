<div class="mx-auto w-full max-w-6xl px-4">
    <md-linear-progress wire:loading wire:target='save' class="fixed top-0 w-full left-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    @if ($this->canAccess())
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="order-last sm:col-span-1">
                <div class="rounded-2xl border border-ink-200 bg-white shadow-sm mb-4">
                    <div class="p-4">
                        <div class="mb-3">
                            <label for="keterangan" class="mb-1 block text-sm font-semibold text-ink-700">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="5" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('detail_keterangan')in-invalid @enderror" wire:model.live='detail_keterangan'></textarea>
                            @error('detail_keterangan')
                                <div class="text-red-600 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        @if (count($persyaratan) > 0 || $detail_keterangan)
                            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white text-nowrap" wire:loading.remove wire:target='save' wire:click='save'>Simpan</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="sm:col-span-1">
                <div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
                    <div class="border-b border-ink-200 px-4 py-3 pb-3 border-b">
                        <h5 class="capitalize mb-0">{{ App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN }}</h5>
                        <div class="flex gap-2">
                            <i class="fa-solid fa-arrow-circle-right"></i>
                            <p class="text-sm mb-0">{{ $status->keterangan }}</p>
                        </div>
                    </div>
                    <div class="p-4 pt-3">
                        <div class="img-preview-wrapper">
                            @foreach ($syarat_izin as $syarat)
                                <div class="img-preview-container ipc-md">
                                    @php($img_preview_url = $persyaratan_tmp[$syarat->id] ?? null)
                                    @php($file_path = $syarat->tipe == 'img' ? $syarat->dokumen_pemohon->where('izin_id', $izin->id)->first()->nama_file : $syarat->dokumen_pemohon->where('izin_id', $izin->id)->first()->nama_file_alternatif)
                                    <img src="{{ $img_preview_url ?? \App\Support\SignedFileUrl::make($file_path) }}" class="img-preview">
                                    <div class="img-preview-content">
                                        <h5 class="img-title">{{ "{$syarat->nama} ({$syarat->tipe})" }}</h5>
                                        <div class="mb-1 flex flex-col"
                                            x-data="{ uploading: false, progress: 0 }"
                                            x-on:livewire-upload-start="uploading = true"
                                            x-on:livewire-upload-finish="uploading = false"
                                            x-on:livewire-upload-cancel="uploading = false"
                                            x-on:livewire-upload-error="uploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input type="file" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 hidden" id="syarat-{{ $syarat->id }}" wire:model='persyaratan.{{ $syarat->id }}'>
                                            <div x-show="uploading" class="my-2 h-2 w-full overflow-hidden rounded-full bg-ink-100" role="progressbar" aria-label="Mengunggah berkas" aria-valuemin="0" aria-valuemax="100" x-bind:aria-valuenow="progress">
                                                <div class="h-full rounded-full bg-nest-600 transition-all" x-bind:style="`width: ${progress}%`"></div>
                                            </div>
                                        </div>
                                        @error("persyaratan.{$syarat->id}")
                                            <div class="text-base font-bold text-red-600 mb-3 text-lowercase">{{ str_replace("persyaratan.{$syarat->id}", $syarat->nama, $message) }}</div>
                                        @enderror
                                        <div class="flex justify-start items-center gap-1 mb-3">
                                            @if (!isset($persyaratan[$syarat->id]))
                                                <a href="{{ \App\Support\SignedFileUrl::make($syarat->dokumen_pemohon->where('izin_id', $izin->id)->first()->nama_file) }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs bg-sky-600 text-white" title="download">
                                                    <i class="fa-solid fa-download text-sm"></i>
                                                </a>
                                            @endif
                                            <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs bg-gradient-secondary" onclick="document.getElementById('{{ 'syarat-' . $syarat->id }}').click()" type="button" title="ganti">
                                                <i class="fa-solid fa-upload text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="absolute inset-0 h-full w-full hidden items-center justify-center bg-dark opacity-75 rounded-xl preview-loading-img" id="loading-{{ $syarat->id }}" data-id="{{ $syarat->id }}">
                                        <div class="animate-spin text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
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
        <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
            <p>Izin tidak ditemukan!</p>
        </div>
    @endif
    <div class="hidden" id="pdf-container"></div>
</div>
    @push('scripts')
    <script>
        Livewire.on('start-loading', (data) => {
            const loadingId = "loading-" + data[0].id
            loadingEl = document.getElementById(loadingId)
            setTimeout(() => {
                loadingEl.classList.remove('hidden')
                loadingEl.classList.add('flex')
                searchIdsToKeep()
            }, 100);
        })
        let loadingIds = []
        function searchIdsToKeep() {
            let keepIdsInterval = setInterval(() => {
                let loadingPreviews = document.querySelectorAll('.preview-loading-img')
                loadingPreviews.forEach(loadingPreview => {
                    if (loadingPreview.classList.contains('flex')) {
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
                    if (!loadingEl.classList.contains('flex')) {
                        loadingEl.classList.add('flex')
                        loadingEl.classList.remove('hidden')
                        clearInterval(keepIdsInterval)
                    }
                })
            }, 500);
        }
        Livewire.on('stop-loading', (data) => {
            const loadingId = "loading-" + data[0].id
            loadingEl = document.getElementById(loadingId)
            setTimeout(() => {
                loadingEl.classList.remove('flex')
                loadingEl.classList.add('hidden')
                loadingIds = []
            }, 100);
        })
    </script>
    @endpush
