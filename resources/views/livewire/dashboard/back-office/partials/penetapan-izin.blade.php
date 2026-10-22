<div class="modal-right hidden">
    <div class="modal-right-content modal-right-content-md flex">
        <div class="w-full">
            <div class="mb-5 border-b border-ink-100 pb-4">
                <p class="text-xs font-extrabold uppercase tracking-widest text-nest-700">Back Office</p>
                <h2 class="mt-1 text-xl font-extrabold text-ink-900">Penetapan Izin</h2>
                <p class="mt-1 text-sm text-ink-500">Tinjau detail sebelum menetapkan keputusan.</p>
            </div>
            @if ($selectedPreview)
                <dl class="grid gap-4 rounded-xl bg-ink-50 p-4 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold text-ink-500">Nama izin</dt><dd class="mt-1 font-bold text-ink-900">{{ str_capitalize($selectedPreview->jenis_perizinan->nama, true) }}</dd></div>
                    <div><dt class="text-xs font-semibold text-ink-500">Pemohon</dt><dd class="mt-1 font-bold capitalize text-ink-900">{{ $selectedPreview->pemohon->nama_pemohon }}</dd></div>
                    <div><dt class="text-xs font-semibold text-ink-500">Lokasi izin</dt><dd class="mt-1 font-bold capitalize text-ink-900">{{ $selectedPreview->lokasi_izin }}</dd></div>
                    <div><dt class="text-xs font-semibold text-ink-500">Nomor surat</dt><dd class="mt-1 font-bold capitalize text-ink-900">{{ $selectedPreview->no_surat }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs font-semibold text-ink-500">Penandatangan</dt><dd class="mt-1 font-bold capitalize text-ink-900">{{ $selectedPreview->penandatangan->nama }}</dd></div>
                </dl>
                <p class="mt-3 text-sm">Riwayat Proses</p>
                <div class="grid grid-cols-1 gap-4 justify-start">
                    @foreach ($previewRiwayatProses as $proses => $data)
                        <div class="min-w-0">
                            <p class="text-sm font-bold">{{ $proses }}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="mb-0{{ $data['status'] ? ' text-emerald-600' : ' text-amber-600' }}">
                                <i class="fa-solid {{ $data['status'] ? 'fa-circle-check' : 'fa-circle-exclamation' }} ml-1"></i>
                                {{ isset($data['keterangan']) ? $data['keterangan'] : 'selesai' }}
                            </p>
                            <p class="text-sm font-bold">
                                <i class="fa-solid fa-user-tie ml-2"></i>
                                {{ $data['pegawai'] }}
                            </p>
                            <p class="mb-0 text-sm text-sm">
                                <i class="fa-regular fa-calendar-check ml-2"></i>
                                {{ $data['tanggal'] }}
                            </p>
                        </div>
                        <hr class="mx-2">
                    @endforeach
                </div>
                @if (in_array($selectedPreview->status_izin->last()->keterangan, [App\utils\enums\BackOfficeStatusEnum::DIREKOM, App\utils\enums\BackOfficeStatusEnum::REKOM_CATATAN]))
                    <div class="rounded-xl border p-4">
                        <div class="mb-4">
                            <form wire:submit.prevent='save'>
                                <p class="text-sm font-bold">Tindakan Izin</p>
                                <div class="flex items-center">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" class="h-4 w-4 accent-nest-700" name="tindakan" id="izinkan" value="{{ App\utils\enums\BackOfficeStatusEnum::DIIZINKAN }}" wire:model='tindakan'>
                                        <label class="flex items-center gap-2" for="izinkan">Izinkan</label>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input type="radio" class="h-4 w-4 accent-nest-700" name="tindakan" id="tolak" value="{{ App\utils\enums\BackOfficeStatusEnum::LAYANAN_DITOLAK }}" wire:model='tindakan'>
                                        <label class="flex items-center gap-2" for="tolak">Tolak Layanan</label>
                                    </div>
                                </div>
                                @error('tindakan')
                                    <div class="text-sm text-red-600">{{ $message }}</div>
                                @enderror
                                <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs bg-nest-700 text-white" type="submit">simpan</button>
                            </form>
                        </div>
                        <div class="bg-nest-50 text-nest-700 ring-1 ring-nest-200 rounded-xl p-4 text-sm">
                            Pastikan Anda memilih tindakan (izinkan atau tolak) untuk menyelesaikan proses permohonan izin ini.
                        </div>
                    </div>
                @elseif ($selectedPreview->status_izin->last()->keterangan == App\utils\enums\BackOfficeStatusEnum::DIIZINKAN || !$this->isAlreadyPrinted())
                    <form action="{{ route('pegawai.download-surat') }}" method="POST">
                        @csrf
                        <input type="hidden" class="hidden" name="id" value="{{ encrypt_id($selectedPreview->id) }}">
                        <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs bg-sky-600 text-white" type="submit">
                            <i class="fa-solid fa-print ml-1 text-base"></i>
                            Cetak Surat Izin
                        </button>
                    </form>
                @endif
                <div class="p-3"></div>
            @endif
        </div>
        <button class="ml-auto text-base inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-transparent p-0 rounded-full h-9 w-9 sticky top-0" wire:click='closePreview' id="modalRightCloseBtn">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div wire:loading wire:target='previewDataIzin, save'>
            <div class="fixed top-0 right-0 bottom-0 bg-white opacity-75 flex items-center justify-center modal-right-loading">
                <i class="fa-solid fa-ellipsis fa-fade text-2xl"></i>
            </div>
        </div>
    </div>
</div>
