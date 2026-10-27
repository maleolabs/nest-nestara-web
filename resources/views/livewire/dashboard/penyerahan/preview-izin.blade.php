@if ($selectedPreview)
<div wire:key="penyerahan-preview-{{ $selectedPreview->id }}" id="penyerahanPreviewDialog" x-data="{ open: true, previous: null, close() { $wire.closePreview() }, trap(event) { const f = [...$el.querySelectorAll('button,a,input,select,textarea,[tabindex]')].filter(el => !el.disabled && el.getAttribute('tabindex') !== '-1'); if (event.key !== 'Tab' || !f.length) return; if (event.shiftKey && document.activeElement === f[0]) { event.preventDefault(); f.at(-1).focus() } else if (!event.shiftKey && document.activeElement === f.at(-1)) { event.preventDefault(); f[0].focus() } } }" x-init="previous = document.activeElement; document.body.classList.add('overflow-hidden'); $nextTick(() => $refs.close.focus()); return () => { document.body.classList.remove('overflow-hidden'); previous?.focus?.() }" @keydown.escape.window="close()" @keydown.tab="trap($event)" @click.self="close()" class="fixed inset-0 z-50 flex items-center justify-center bg-ink-900/50 p-4" role="dialog" aria-modal="true" aria-labelledby="penyerahanPreviewTitle">
    <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2 scale-[.98]" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="relative flex max-h-[min(90vh,760px)] w-full max-w-3xl overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-ink-200">
        <div class="w-full h-full flex flex-col bg-white">
            {{-- header --}}
            <div class="flex items-center justify-between gap-3 border-b border-ink-100 px-5 py-4">
                <div>
                    <p class="text-[11px] font-extrabold tracking-widest text-nest-700 uppercase">Penyerahan</p>
                    <h4 id="penyerahanPreviewTitle" class="font-display font-extrabold text-[18px] leading-tight text-ink-900">Preview Izin</h4>
                </div>
                <button x-ref="close" type="button" @click="close()"
                    aria-label="Tutup preview penyerahan" aria-controls="penyerahanPreviewDialog"
                    class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-ink-50 text-ink-600 ring-1 ring-ink-200 hover:bg-ink-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-5">
                @php
                    $previewStatus = $selectedPreview?->latestStatus;
                @endphp

                @if ($previewStatus && $previewStatus->nama === $statusIzin::PENYERAHAN && $previewStatus->progres === $statusProgres::FINISHED)
                        {{-- celebration --}}
                        <div class="flex flex-col items-center justify-center text-center py-8">
                            <img src="{{ asset('storage/web/celebration-sd.gif') }}" alt="" class="w-40 h-auto">
                            <span class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-4 py-1.5 text-sm font-extrabold text-emerald-700 ring-1 ring-emerald-200">
                                <i class="fa-solid fa-circle-check"></i> Izin telah diserahkan
                            </span>
                            <p class="mt-2 max-w-[36ch] text-xs leading-relaxed text-ink-500">Proses penyerahan dokumen ini sudah selesai dan tercatat dalam arsip sistem.</p>
                            <div class="mt-5 flex flex-wrap justify-center gap-2">
                                <a href="{{ route('preview-izin', ['izin_id' => encrypt_id($selectedPreview->id)]) }}"
                                    class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-nest-800 transition">
                                    Lihat Detail Izin <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                                <button type="button" @click="close()"
                                    class="inline-flex items-center gap-2 rounded-full bg-ink-100 px-5 py-2.5 text-sm font-bold text-ink-700 hover:bg-ink-200 transition">
                                    Tutup
                                </button>
                            </div>
                        </div>
                @elseif ($previewStatus && $previewStatus->nama === $statusIzin::PENYERAHAN)
                    <div class="flex flex-col items-center justify-center text-center py-12">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-50 grid place-items-center text-emerald-600 ring-1 ring-emerald-200"><i class="fa-solid fa-hand-holding-heart text-xl"></i></div>
                        <p class="mt-3 text-sm font-bold text-ink-900">Surat izin siap diserahkan</p>
                        <p class="mt-1 max-w-[34ch] text-xs leading-relaxed text-ink-500">Dokumen sudah terbit dan menunggu penyerahan kepada pemohon.</p>
                    </div>
                @endif

                @if ($previewStatus && ($previewStatus->nama !== $statusIzin::PENYERAHAN || $previewStatus->progres === $statusProgres::PROCESSING))
                        {{-- identitas izin --}}
                        <div class="rounded-2xl bg-gradient-to-br from-nest-700 to-nest-800 p-4 text-white">
                            <p class="text-[11px] font-bold tracking-widest uppercase text-white/70">Jenis Perizinan</p>
                            <h3 class="mt-1 font-display font-extrabold text-[17px] leading-snug">{{ str_capitalize($selectedPreview->jenis_perizinan->nama, true) }}</h3>
                            @php
                                $sc = getStatusIzinClasses($previewStatus);
                            @endphp
                            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-extrabold">
                                <i class="{{ $sc['bgClass'] }} h-1.5 w-1.5 rounded-full {{ $previewStatus->nama == $statusIzin::PENERBITAN ? 'bg-white' : '' }}"></i>
                                {{ $previewStatus->nama }}
                            </span>
                        </div>

                        {{-- detail data --}}
                        <dl class="mt-5 space-y-3 text-sm">
                            <div class="flex items-start justify-between gap-3">
                                <dt class="shrink-0 text-xs font-bold tracking-wide text-ink-500 uppercase">Pemohon</dt>
                                <dd class="text-right font-bold capitalize text-ink-900">{{ $selectedPreview->pemohon->nama_pemohon }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 border-t border-dashed border-ink-200 pt-3">
                                <dt class="shrink-0 text-xs font-bold tracking-wide text-ink-500 uppercase">No. Pendaftaran</dt>
                                <dd class="text-right font-semibold text-ink-800">{{ $selectedPreview->no_pendaftaran }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 border-t border-dashed border-ink-200 pt-3">
                                <dt class="shrink-0 text-xs font-bold tracking-wide text-ink-500 uppercase">Tanggal Permohonan</dt>
                                <dd class="text-right font-semibold text-ink-800">{{ $this->getTanggalPermohonan($selectedPreview) }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 border-t border-dashed border-ink-200 pt-3">
                                <dt class="shrink-0 text-xs font-bold tracking-wide text-ink-500 uppercase">Lokasi Izin</dt>
                                <dd class="text-right font-medium capitalize text-ink-700">{{ $selectedPreview->lokasi_izin ?? '—' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 border-t border-dashed border-ink-200 pt-3">
                                <dt class="shrink-0 text-xs font-bold tracking-wide text-ink-500 uppercase">Nomor Surat</dt>
                                <dd class="text-right font-semibold {{ $selectedPreview->no_surat ? 'text-ink-800' : 'italic text-ink-400' }}">{{ $selectedPreview->no_surat ?? 'Belum terbit' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-3 border-t border-dashed border-ink-200 pt-3">
                                <dt class="shrink-0 text-xs font-bold tracking-wide text-ink-500 uppercase">Penandatangan</dt>
                                <dd class="text-right font-medium capitalize text-ink-700">{{ $selectedPreview->penandatangan?->nama ?? '—' }}</dd>
                            </div>
                        </dl>

                        {{-- aksi status --}}
                        @if ($previewStatus->nama === $statusIzin::PROSES && in_array($previewStatus->keterangan, [\App\utils\enums\BackOfficeStatusEnum::DIIZINKAN, \App\utils\enums\BackOfficeStatusEnum::DICETAK, \App\utils\enums\BackOfficeStatusEnum::IZIN_AKAN_DITERBITKAN], true))
                            <div class="mt-5 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200">
                                <p class="text-sm font-bold text-amber-800"><i class="fa-solid fa-forward mr-1.5"></i>Surat izin siap diserahkan</p>
                                <p class="mt-1 text-xs leading-relaxed text-amber-800/80">Surat sudah dicetak. Lanjutkan ke proses penyerahan.</p>
                                <button type="button" wire:click='printedPermitReadyForHandover({{ $selectedPreview->id }})' class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-full bg-amber-500 px-4 py-2.5 text-sm font-extrabold text-white hover:bg-amber-600 transition disabled:opacity-50" wire:loading.attr="disabled" wire:target='printedPermitReadyForHandover'>
                                    <span wire:loading.remove wire:target='printedPermitReadyForHandover'><i class="fa-solid fa-forward"></i> Ya, surat izin siap diserahkan</span>
                                    <span wire:loading wire:target='printedPermitReadyForHandover'><i class="fa-solid fa-spinner fa-spin"></i> Memperbarui…</span>
                                </button>
                            </div>
                        @elseif ($previewStatus->nama === $statusIzin::PENERBITAN && $previewStatus->progres === $statusProgres::PROCESSING)
                            <div class="mt-5 rounded-xl bg-amber-50 p-4 ring-1 ring-amber-200">
                                <p class="text-sm font-bold text-amber-800"><i class="fa-solid fa-pen-nib mr-1.5"></i>Proses penandatanganan berlangsung</p>
                                <p class="mt-1 text-xs leading-relaxed text-amber-800/80">Pastikan seluruh informasi pada surat izin telah diverifikasi sebelum melanjutkan.</p>
                                <button type="button" wire:click='penerbitanFinished({{ $selectedPreview->id }})'
                                    class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-full bg-amber-500 px-4 py-2.5 text-sm font-extrabold text-white hover:bg-amber-600 transition disabled:opacity-50"
                                    wire:loading.attr="disabled" wire:target='penerbitanFinished'>
                                    <span wire:loading.remove wire:target='penerbitanFinished'><i class="fa-solid fa-forward"></i> Ya, surat izin siap diserahkan</span>
                                    <span wire:loading wire:target='penerbitanFinished'><i class="fa-solid fa-spinner fa-spin"></i> Memperbarui…</span>
                                </button>
                            </div>
                        @elseif ($previewStatus->nama === $statusIzin::PENYERAHAN && $previewStatus->progres === $statusProgres::PROCESSING)
                            <div class="mt-5 rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-200">
                                <p class="text-sm font-bold text-emerald-800"><i class="fa-solid fa-hand-holding-heart mr-1.5"></i>Surat izin siap diserahkan</p>
                                <p class="mt-1 text-xs leading-relaxed text-emerald-800/80">Serahkan dokumen kepada pemohon, lalu tandai selesai agar proses terdokumentasi.</p>
                                <button type="button" wire:click='allDone({{ $selectedPreview->id }})'
                                    class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-full bg-emerald-600 px-4 py-2.5 text-sm font-extrabold text-white hover:bg-emerald-700 transition disabled:opacity-50"
                                    wire:loading.attr="disabled" wire:target='allDone'>
                                    <span wire:loading.remove wire:target='allDone'><i class="fa-solid fa-check-double"></i> Ya, surat izin telah diserahkan</span>
                                    <span wire:loading wire:target='allDone'><i class="fa-solid fa-spinner fa-spin"></i> Memperbarui…</span>
                                </button>
                            </div>
                        @endif

                        <div class="mt-4 flex items-start gap-2 rounded-lg bg-ink-50 px-3 py-2.5 ring-1 ring-ink-200">
                            <i class="fa-solid fa-shield-halved mt-0.5 text-xs text-nest-700"></i>
                            <p class="text-[11px] leading-relaxed text-ink-600">Perubahan status tercatat dalam jejak audit — lengkap dengan waktu & pegawai.</p>
                        </div>
                @endif

                 @if (! $selectedPreview)
                    {{-- empty state --}}
                    <div class="flex h-full flex-col items-center justify-center text-center py-12">
                        <div class="h-14 w-14 rounded-2xl bg-ink-50 grid place-items-center text-ink-400 ring-1 ring-ink-200"><i class="fa-solid fa-file-circle-question text-xl"></i></div>
                        <p class="mt-3 text-sm font-bold text-ink-900">Tidak ada izin dipilih</p>
                        <p class="mt-1 max-w-[30ch] text-xs leading-relaxed text-ink-500">Klik salah satu baris permohonan pada tabel untuk melihat preview di panel ini.</p>
                    </div>
                  @elseif ($selectedPreview && !$previewStatus)
                    <div role="alert" class="rounded-xl bg-red-50 p-4 text-sm text-red-800 ring-1 ring-red-200">Status izin tidak tersedia. Muat ulang atau hubungi administrator.</div>
                 @endif
            </div>
        </div>

        {{-- loading overlay panel --}}
        <div wire:loading wire:target='previewDataIzin, penerbitanFinished, allDone'
            class="absolute inset-0 z-10 grid place-items-center bg-white/75 backdrop-blur-[2px]">
            <div class="flex flex-col items-center gap-2">
                <i class="fa-solid fa-spinner fa-spin text-xl text-nest-700"></i>
                <p class="text-xs font-bold text-ink-500">Memuat data izin…</p>
            </div>
        </div>
    </div>
</div>
@endif
