<div>
    <p class="text-xs text-muted mb-3 pb-1 border-bottom">Tindakan</p>
    <div class="border border rounded-3 p-4">
        <div class="row align-items-center mb-3">
            @if ($editing_name)
                <div class="col-12">
                    <div class="border rounded p-4">
                        <form wire:submit.prevent='saveNamaJenis'>
                            <p class="mb-1 text-sm text-medium">Ganti Nama Jenis Perizinan</p>
                            <div class="input-group input-group-sm w-50 border rounded-3 mb-3">
                                <input type="text" class="form-control border-0" placeholder="masukkan nama jenis perizinan" wire:model.lazy='nama_jenis'>
                            </div>
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn btn-sm bg-gradient-light text-capitalize" type="button" wire:click='editNamaJenis'>
                                    <span wire:loading.remove wire:target='editNamaJenis'>batal</span>
                                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='editNamaJenis'></span>
                                </button>
                                <button class="btn btn-sm bg-gradient-primary text-capitalize" type="submit">
                                    <span wire:loading.remove wire:target='saveNamaJenis'>simpan</span>
                                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='saveNamaJenis'></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="col-10">
                    <p class="mb-0 text-sm fw-semibold">
                        Ubah nama perizinan "{{ str_capitalize($preview_jenis->nama, true) }}"
                    </p>
                    <small>
                        Perubahan pada nama jenis perizinan ini dapat memiliki dampak terhadap proses administrasi yang terkait. Oleh karena itu, harap berhati-hati dan pertimbangkan dengan matang sebelum melanjutkan.
                    </small>
                </div>
                <button class="btn btn-sm btn-outline-secondary text-capitalize text-nowrap col-2" type="button" wire:click='editNamaJenis'>
                    <span wire:loading.remove wire:target='editNamaJenis'>ubah</span>
                    <span wire:loading wire:target='editNamaJenis' class="spinner-border spinner-border-sm"></span>
                </button>
            @endif
        </div>
        <div class="row align-items-center">
            @if ($deleting)
                <div class="border rounded p-4 d-flex align-items-center justify-content-center gap-2">
                    <button class="btn btn-sm bg-gradient-light text-capitalize" type="button" wire:click='deleteJenis'>
                        <span wire:loading.remove wire:target='deleteJenis'>batal</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='deleteJenis'></span>
                    </button>
                    <button class="btn btn-sm bg-gradient-danger text-capitalize" type="button" wire:click='confirmDelete'>
                        <span wire:loading.remove wire:target='confirmDelete'>hapus</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='confirmDelete'></span>
                    </button>
                </div>
            @else
                <div class="col-10 text-danger">
                    <p class="mb-0 text-sm fw-semibold">
                        Hapus perizinan "{{ str_capitalize($preview_jenis->nama, true) }}"
                    </p>
                    <small>
                        Tindakan ini bersifat permanen dan dapat mempengaruhi berbagai aspek operasional dan administratif. Harap berhati-hati dan pertimbangkan dengan matang sebelum melanjutkan.
                    </small>
                </div>
                <button class="btn btn-sm btn-outline-danger text-capitalize text-nowrap col-2" type="button" wire:click='deleteJenis'>
                    <span wire:loading.remove wire:target='deleteJenis'>hapus</span>
                    <span class="spinner-border spinner-border-sm" wire:loading wire:target='deleteJenis'></span>
                </button>
            @endif
        </div>
    </div>
</div>