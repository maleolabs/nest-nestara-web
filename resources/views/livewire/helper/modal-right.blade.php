<div x-data="{ open: @js((bool) $selectedPreview), previous: null, close() { this.open = false; $wire.closePreview() }, trap(event) { if (event.key !== 'Tab') return; const focusable = [...$el.querySelectorAll('button,a,input,select,textarea,[tabindex]:not([tabindex=\"-1\"])')].filter(el => !el.disabled); if (!focusable.length) return; const first = focusable[0], last = focusable[focusable.length - 1]; if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus() } else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus() } } }" x-init="previous = document.activeElement; const lock = value => document.body.classList.toggle('overflow-hidden', value); lock(open); $watch('open', value => { lock(value); if (value) $nextTick(() => $refs.close.focus()); else previous?.focus?.() }); return () => { lock(false); previous?.focus?.() }" x-effect="open = @js((bool) $selectedPreview)" x-show="open" x-cloak x-transition.opacity @keydown.escape.window="close()" @keydown.tab="trap($event)" @click.self="close()" class="fixed inset-0 z-50 bg-ink-900/40" role="dialog" aria-modal="true" aria-labelledby="modalRightTitle">
    <div class="flex max-h-full w-full max-w-xl overflow-y-auto bg-white p-5 shadow-2xl" @click.stop>
        <div class="w-full min-w-0">
            <h4 class="mb-5 border-b border-ink-100 pb-4 text-lg font-extrabold text-ink-900" id="modalRightTitle">Lihat pendaftaran izin</h4>
            @if ($selectedPreview)
                <p class="text-xs font-bold uppercase tracking-wide text-ink-500">Nama Izin</p>
                <h3 class="mt-1 text-lg font-extrabold text-ink-900">{{ str_capitalize($selectedPreview->jenis_perizinan->nama, true) }}</h3>
                <p class="mt-4 text-xs font-bold uppercase tracking-wide text-ink-500">Kelompok Izin</p>
                <h6 class="mt-1 capitalize text-sm font-bold text-ink-800">{{ $selectedPreview->jenis_perizinan->kelompok_izin }}</h6>
                <h6 class="mt-4 flex items-center gap-2 text-sm font-semibold text-ink-700">
                    <i class="fa-solid fa-hourglass-half mr-1"></i>
                    Durasi pengerjaan {{ $selectedPreview->jenis_perizinan->durasi }}
                </h6>
                <h6 class="mt-2 flex items-center gap-2 text-sm font-semibold text-ink-700">
                    <i class="fa-regular fa-calendar-check mr-1"></i>
                    Masa berlaku izin {{ $selectedPreview->jenis_perizinan->masa_berlaku }}
                </h6>
                <p class="mt-5 mb-2 text-xs font-bold uppercase tracking-wide text-ink-500">Status izin</p>
                <div>
                    @foreach ($selectedPreview->status_izin as $status)
                        {!! renderStatusIzinWithIconAndDetails($status, false, $this->isIzinCancelable($status->nama) , false) !!}
                    @endforeach
                </div>
                @php($izin_last_status = $selectedPreview->status_izin->last())
                @if ($izin_last_status->nama == App\utils\enums\StatusIzinEnum::BATAL)
                    <div class="mt-4 rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">
                        <p>Permohonan izin ini telah dibatalkan. Anda dapat menghapusnya.</p>
                        <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-red-600 text-white flex items-center" type="button" wire:click='delete({{ $selectedPreview->id }})'>
                            <span wire:loading.remove wire:target='delete'>hapus permohonan izin</span>
                            <div class="ml-1 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent" role="status" wire:loading wire:target='delete'>
                                <span class="sr-only">Memuat</span>
                            </div>
                        </button>
                    </div>
                @elseif ($izin_last_status->nama == App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN)
                    <div class="mt-4 rounded-xl bg-amber-50 p-4 text-sm text-amber-900 ring-1 ring-amber-200">
                        <p>{{ $izin_last_status->keterangan }}</p>
                        <a href="{{ route('user.revisi-izin', ['izin_id' => encrypt_id($selectedPreview->id)]) }}" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-amber-500 text-white">
                            buka halaman perbaikan
                            <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                        </a>
                    </div>
                @else
                    <div class="mt-4 rounded-xl bg-nest-50 p-4 text-sm text-nest-700 ring-1 ring-nest-200">
                        <strong>Informasi:</strong>
                        <p class="mb-0">
                            Dokumen Anda akan divalidasi terlebih dahulu, kemudian akan diproses oleh petugas, dan izin Anda akan diterbitkan setelah semua tahap selesai.
                        </p>
                    </div>  
                @endif
                <p class="mt-5 mb-2 text-xs font-bold uppercase tracking-wide text-ink-500">Persyaratan</p>
                @foreach ($selectedPreviewSyaratIzin as $syarat)
                            <h6 class="flex items-center gap-2 border-b border-ink-100 py-2 text-sm @if(!isset($syarat->dokumen_pemohon[0]))text-ink-500 line-through @else text-ink-800 @endif">
                        @if (isset($syarat->dokumen_pemohon[0]))
                                <a href="{{ \App\Support\SignedFileUrl::make($syarat->dokumen_pemohon[0]->nama_file) }}" class="inline">
                                    <i class="fa-solid fa-download mr-1"></i>
                            </a>
                        @else
                            <i class="fa-solid fa-download mr-1"></i>
                        @endif
                        {{ $syarat->nama }}
                    </h6>
                @endforeach
                <a href="javascript:;">
                    lihat semua dokumen Anda
                    <i class="fa-solid fa-arrow-up-right-from-square ml-1"></i>
                </a>
            @endif
            <div class="h-3"></div>
        </div>
        <button x-ref="close" type="button" aria-label="Tutup preview" class="ml-3 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-ink-500 hover:bg-ink-50" @click="close()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    <div wire:loading wire:target='preview'>
        <div class="absolute top-0 right-0 bottom-0 bg-white opacity-75 flex items-center justify-center modal-right-loading">
            <i class="fa-solid fa-spinner fa-spin text-xl text-nest-700" aria-hidden="true"></i>
        </div>
    </div>
</div>
