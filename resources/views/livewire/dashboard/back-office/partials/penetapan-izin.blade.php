<div
    x-data="{
        open: true,
        closing: false,
        close() {
            if (this.closing) return
            this.closing = true
            this.open = false
            setTimeout(() => $wire.closePreview(), 200)
        }
    }"
    x-on:keydown.escape.window="close()"
>
    @if ($selectedPreview)
        <div
            x-cloak
            x-show="open"
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-ink-950/60"
            role="presentation"
            x-on:click.self="close()"
            aria-hidden="true"
        ></div>

        <section
            x-ref="drawer"
            x-init="$nextTick(() => $refs.close.focus())"
            x-cloak
            x-show="open"
            x-transition:enter="transform transition ease-out duration-200"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 z-50 flex w-full max-w-xl flex-col bg-white shadow-2xl ring-1 ring-ink-200/80 sm:w-[min(92vw,36rem)]"
            role="dialog"
            aria-modal="true"
            aria-labelledby="penetapan-drawer-title"
            tabindex="-1"
            wire:key="penetapan-drawer-{{ $selectedPreview->id }}"
            wire:click.stop
        >
            <header class="flex shrink-0 items-start justify-between gap-4 border-b border-ink-200 px-5 py-4 sm:px-6">
                <div class="min-w-0">
                    <p class="text-xs font-extrabold uppercase tracking-widest text-nest-700">Back Office · Penetapan</p>
                    <h2 id="penetapan-drawer-title" class="mt-1 truncate text-xl font-extrabold text-ink-900">Penetapan izin</h2>
                    <p class="mt-1 text-sm text-ink-500">Tinjau konteks dan tetapkan keputusan permohonan.</p>
                </div>
                <button x-ref="close" type="button" x-on:click="close()" x-bind:disabled="closing" aria-label="Tutup penetapan" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-xl text-ink-500 hover:bg-ink-100 focus:outline-none focus:ring-2 focus:ring-nest-600 disabled:cursor-wait disabled:opacity-60">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </header>

            <div class="min-h-0 flex-1 overflow-y-auto px-5 py-5 sm:px-6">
                <dl class="grid gap-4 rounded-2xl bg-ink-50 p-4 sm:grid-cols-2">
                    <div><dt class="text-xs font-semibold text-ink-500">Nama izin</dt><dd class="mt-1 font-bold text-ink-900">{{ str_capitalize($selectedPreview->jenis_perizinan?->nama ?? '—', true) }}</dd></div>
                    <div><dt class="text-xs font-semibold text-ink-500">Pemohon</dt><dd class="mt-1 font-bold capitalize text-ink-900">{{ $selectedPreview->pemohon?->nama_pemohon ?? '—' }}</dd></div>
                    <div><dt class="text-xs font-semibold text-ink-500">Lokasi izin</dt><dd class="mt-1 font-bold capitalize text-ink-900">{{ $selectedPreview->lokasi_izin }}</dd></div>
                    <div><dt class="text-xs font-semibold text-ink-500">Nomor surat</dt><dd class="mt-1 font-bold text-ink-900">{{ $selectedPreview->no_surat }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs font-semibold text-ink-500">Penandatangan</dt><dd class="mt-1 font-bold capitalize text-ink-900">{{ $selectedPreview->penandatangan?->nama ?? '—' }}</dd></div>
                </dl>

                <div class="mt-6">
                    <h3 class="text-sm font-extrabold text-ink-900">Riwayat proses</h3>
                    <ol class="mt-3 divide-y divide-ink-100 rounded-2xl border border-ink-200">
                        @foreach ($previewRiwayatProses as $proses => $data)
                            <li class="grid gap-1 px-4 py-3 sm:grid-cols-[9rem_1fr]">
                                <p class="text-sm font-bold text-ink-800">{{ $proses }}</p>
                                <div class="text-sm">
                                    <p class="font-semibold {{ $data['status'] ? 'text-emerald-600' : 'text-amber-600' }}"><i class="fa-solid {{ $data['status'] ? 'fa-circle-check' : 'fa-circle-exclamation' }} mr-1" aria-hidden="true"></i>{{ $data['keterangan'] ?? 'Selesai' }}</p>
                                    <p class="mt-1 text-ink-600"><i class="fa-solid fa-user-tie mr-1" aria-hidden="true"></i>{{ $data['pegawai'] }}</p>
                                    <p class="text-ink-500"><i class="fa-regular fa-calendar-check mr-1" aria-hidden="true"></i>{{ $data['tanggal'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                @php($latestStatus = $selectedPreview->status_izin->last())
                @if (in_array($latestStatus?->keterangan, [App\utils\enums\BackOfficeStatusEnum::DIREKOM, App\utils\enums\BackOfficeStatusEnum::REKOM_CATATAN]))
                    <form wire:submit.prevent="save" class="mt-6 rounded-2xl border border-ink-200 p-4">
                        <h3 class="text-sm font-extrabold text-ink-900">Keputusan</h3>
                        <fieldset class="mt-3 grid gap-2 sm:grid-cols-2">
                            <legend class="sr-only">Pilih tindakan izin</legend>
                            <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold hover:bg-ink-50"><input type="radio" value="{{ App\utils\enums\BackOfficeStatusEnum::DIIZINKAN }}" wire:model="tindakan" class="h-4 w-4 accent-nest-700"> Izinkan</label>
                            <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-ink-200 p-3 text-sm font-semibold hover:bg-ink-50"><input type="radio" value="{{ App\utils\enums\BackOfficeStatusEnum::LAYANAN_DITOLAK }}" wire:model="tindakan" class="h-4 w-4 accent-nest-700"> Tolak layanan</label>
                        </fieldset>
                        @error('tindakan') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-4 rounded-xl bg-nest-50 p-3 text-sm text-nest-700 ring-1 ring-nest-200">Pastikan tindakan sesuai hasil pemeriksaan sebelum menyimpan.</p>
                        <footer class="sticky bottom-0 -mx-4 mt-4 border-t border-ink-200 bg-white px-4 pt-4"><button type="submit" class="w-full rounded-xl bg-nest-700 px-4 py-3 text-sm font-bold text-white hover:bg-nest-800 focus:outline-none focus:ring-2 focus:ring-nest-600 focus:ring-offset-2">Simpan keputusan</button></footer>
                    </form>
                @elseif ($latestStatus?->keterangan == App\utils\enums\BackOfficeStatusEnum::DIIZINKAN || !$this->isAlreadyPrinted())
                    <form action="{{ route('pegawai.download-surat') }}" method="POST" class="mt-6">@csrf<input type="hidden" name="id" value="{{ encrypt_id($selectedPreview->id) }}"><button type="submit" class="w-full rounded-xl bg-sky-600 px-4 py-3 text-sm font-bold text-white hover:bg-sky-700"><i class="fa-solid fa-print mr-2" aria-hidden="true"></i>Cetak surat izin</button></form>
                @endif
            </div>

            <div wire:loading wire:target="previewDataIzin, save" class="absolute inset-0 z-10 grid place-items-center bg-white/80" role="status" aria-live="polite"><span class="text-sm font-semibold text-ink-700">Memproses…</span></div>
        </section>
    @endif
</div>
