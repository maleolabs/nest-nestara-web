<div class="modal-right d-none">
    <div class="modal-right-content modal-right-content-md d-flex">
        <div class="w-100">
            @if ($selectedPreview)
                @php($previewStatus = $selectedPreview->status_izin->last())
                @if ($previewStatus->nama == $statusIzin::PENYERAHAN && $previewStatus->progres == $statusProgres::FINISHED)
                    <img src="{{ asset('storage/web/celebration-sd.gif') }}" class="gif-modal-md">
                    <div class="position-absolute top-50 start-50 translate-middle text-center" style="z-index: 10000">
                        <h3 class="text-center">Izin telah diserahkan</h3>
                        <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($selectedPreview->id)]) }}" class="btn bg-gradient-primary mt-3">lihat detail izin</a>
                    </div>
                @else    
                    <h4 class="mb-4 spacing-md">Preview Izin</h4>
                    <p class="mb-0 text-sm">Nama Izin</p>
                    <h3 class="spacing-md">{{ str_capitalize($selectedPreview->jenis_perizinan->nama, true) }}</h3>
                    <p class="mb-0 text-sm">Pemohon</p>
                    <h6 class="text-capitalize">{{ $selectedPreview->pemohon->nama_pemohon }}</h6>
                    <p class="mb-0 text-sm">Tanggal Permohonan</p>
                    <h6 class="text-capitalize">{{ $this->getTanggalPermohonan($selectedPreview->id) }}</h6>
                    <p class="mb-0 text-sm">Lokasi Izin</p>
                    <h6 class="text-capitalize">{{ $selectedPreview->lokasi_izin }}</h6>
                    <p class="mb-0 text-sm">Nomor Surat</p>
                    <h6 class="text-capitalize">{{ $selectedPreview->no_surat }}</h6>
                    <p class="mb-0 text-sm">Penandatangan</p>
                    <h6 class="text-capitalize">{{ $selectedPreview->penandatangan->nama }}</h6>

                    @if ($selectedPreview->status_izin->last()->nama == $statusIzin::PENERBITAN)
                        <div class="rounded-3 p-4 bg-app-primary-light mt-4">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Proses penandatanganan surat izin sedang berlangsung. Mohon update status ini setelah penandatanganan izin selesai.
                        </div>

                        <div class="d-flex mt-4 gap-2 text-dark align-items-center">
                            <i class="fa-solid fa-circle-arrow-right"></i>
                            <span>Proses penandatanganan izin telah selesai?</span>
                            <button class="btn btn-sm bg-gradient-info" type="button" wire:click='penerbitanFinished({{ $selectedPreview->id }})'>
                                <span wire:loading.remove wire:target='penerbitanFinished'>
                                    ya, surat izin siap diserahkan
                                </span>
                                <div wire:loading wire:target='penerbitanFinished' class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>

                        <p class="text-sm mt-4">
                            Pastikan semua informasi pada surat izin telah diverifikasi sebelum mengubah status menjadi "siap diserahkan".
                        </p>
                    @else
                        <div class="rounded-3 p-4 bg-app-info-light mt-4">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Surat izin siap untuk diserahkan kepada pemohon. Mohon update status ini setelah surat izin diserahkan untuk memastikan proses telah selesai dan terdokumentasi dengan baik.
                        </div>

                        <div class="d-flex mt-4 gap-2 text-dark align-items-center">
                            <i class="fa-solid fa-circle-arrow-right"></i>
                            <span>Surat izin telah diserahkan?</span>
                            <button class="btn btn-sm bg-gradient-info" type="button" wire:click='allDone({{ $selectedPreview->id }})'>
                                <span wire:loading.remove wire:target='allDone'>
                                    ya, surat izin telah diserahkan
                                </span>
                                <div wire:loading wire:target='allDone' class="spinner-border spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                    @endif
                @endif
                
                <div class="p-3"></div>
            @endif
        </div>
        <button class="ms-auto text-md btn btn-transparent p-0 card-icon sticky-top-0" wire:click='closePreview' id="modalRightCloseBtn" style="z-index: 10001">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div wire:loading wire:target='previewDataIzin, penerbitanFinished'>
            <div class="position-fixed top-0 end-0 bottom-0 bg-white opacity-75 d-flex align-items-center justify-content-center modal-right-loading">
                <i class="fa-solid fa-ellipsis fa-fade fs-1"></i>
            </div>
        </div>
    </div>
</div>