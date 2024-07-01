<div class="p-4">
    <md-linear-progress wire:loading wire:target='selectMenu' class="position-fixed top-0 w-100 start-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>
    <div class="dash-menu sticky-top-0">
        <div class="dash-menu-item {{ $menu == self::DATA_PEMOHON ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PEMOHON }}')" onclick="switchTab('{{ self::DATA_PEMOHON }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-users me-2"></i>
                <span>{{ self::DATA_PEMOHON }}</span>
                @if (count($semua_pemohon))
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ count($semua_pemohon) }}</span>
                @endif
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_IZIN_BARU ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_IZIN_BARU }}')" onclick="switchTab('{{ self::DATA_IZIN_BARU }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-circle-plus me-2"></i>
                <span>{{ self::DATA_IZIN_BARU }}</span>
                @if (count($semua_izin))
                    <span class="ms-2 badge rounded-pill bg-app-primary-light">{{ count($semua_izin) }}</span>
                @endif
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_PERPANJANGAN_IZIN ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PERPANJANGAN_IZIN }}')" onclick="switchTab('{{ self::DATA_PERPANJANGAN_IZIN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-arrow-up me-2"></i>
                <span>{{ self::DATA_PERPANJANGAN_IZIN }}</span>
            </span>
        </div>
        <div class="dash-menu-item {{ $menu == self::DATA_PERUBAHAN_IZIN ? 'active' : '' }}" wire:click="selectMenu('{{ self::DATA_PERUBAHAN_IZIN }}')" onclick="switchTab('{{ self::DATA_PERUBAHAN_IZIN }}')">
            <span class="dash-menu-item-content">
                <i class="fa-solid fa-file-edit me-2"></i>
                <span>{{ self::DATA_PERUBAHAN_IZIN }}</span>
            </span>
        </div>
    </div>
    <div class="content-wrap py-3 px-4">
        @if ($preview_data_izin)
            @livewire('dashboard.pendaftaran.preview-data-izin', ['preview_izin_id' => $preview_izin_id])
        @else
            <div class="input-group input-group-outline my-3">
                <label class="form-label">Cari {{ $menu == self::DATA_PEMOHON ? 'ID atau nama pemohon...' : 'jenis izin, ID, atau nama pemohon...' }}</label>
                <input type="text" class="form-control" wire:model.live='search'>
            </div>
            @if ($layout == 'grid')
                @include('livewire.dashboard.pendaftaran.partials.grid')
            @else
                @include('livewire.dashboard.pendaftaran.partials.table')
            @endif
        @endif
    </div>
</div>
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js" integrity="sha512-Z8CqofpIcnJN80feS2uccz+pXWgZzeKxDsDNMD/dJ6997/LSRY+W4NmEt9acwR+Gt9OHN0kkI1CTianCwoqcjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js" integrity="sha512-lHibs5XrZL9hXP3Dhr/d2xJgPy91f2mhVAasrSbMkbmoTSm2Kz8DuSWszBLUg31v+BM6tSiHSqT72xwjaNvl0g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        Livewire.on('set-preview-img-modal', data => {
            let btnShowModal = document.getElementById('btnShowPreviewModal')
            if (btnShowModal) {
                setTimeout(() => {
                    btnShowModal.click()

                    const res = data[0]
                    let type = res.type
                    if (type == 'pdf') {
                        let fileName = res.path
                        let set_url = `{{ env('APP_URL') }}/files/${fileName}`
                        const pdfContainer = document.getElementById('pdf-container')
    
                        pdfjsLib.getDocument(set_url).promise.then(pdfDoc => {
                            for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                                pdfDoc.getPage(pageNum).then(page => {
                                    const scale = 1.5;
                                    const viewport = page.getViewport({ scale })
    
                                    const canvasWraper = document.createElement('div')
                                    canvasWraper.classList.add('canvas-wraper')
    
                                    const canvas = document.createElement('canvas')
                                    const context = canvas.getContext('2d')
                                    canvas.height = viewport.height
                                    canvas.width = viewport.width
    
                                    pdfContainer.style.width = `${viewport.width + 4}px`
                                    pdfContainer.style.height = `${viewport.height}px`
    
                                    const renderContext = {
                                        canvasContext: context,
                                        viewport: viewport
                                    }
    
                                    canvasWraper.appendChild(canvas)
                                    pdfContainer.appendChild(canvasWraper)
    
                                    page.render(renderContext)
                                })
                            }
                        })
                    }
                }, 100)
            }
        })

        const btnCloseModal = document.getElementById('btn-close-modal')
        if (btnCloseModal) {
            Livewire.dispatch('refresh-component')
        }

        function switchTab(tab) {
            let url = new URL(window.location)
            url.searchParams.set('tab', tab)
            window.history.pushState({}, '', url)
        }
    </script>
@endpush