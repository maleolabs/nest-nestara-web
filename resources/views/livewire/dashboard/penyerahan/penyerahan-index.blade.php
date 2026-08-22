<div>

    {{-- loading indicator: pill kecil, fixed di tengah layar --}}
    <div wire:loading class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[80]" role="status" aria-live="polite">
        <div class="flex items-center gap-3 rounded-full bg-ink-900/90 pl-4 pr-5 py-3 shadow-[0_16px_40px_rgba(15,23,42,0.35)] text-white backdrop-blur-sm">
            <span class="relative flex h-5 w-5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-nest-400 opacity-60"></span>
                <i class="fa-solid fa-spinner fa-spin relative text-sm"></i>
            </span>
            <span class="text-sm font-bold tracking-tight">Memproses…</span>
        </div>
    </div>
    @php
        $statusIzin = \App\utils\enums\StatusIzinEnum::class;
        $statusProgres = \App\utils\enums\StatusIzinProgressEnum::class;
    @endphp

    @include('livewire.dashboard.penyerahan.preview-izin')

    {{-- header --}}
    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Layanan FO</p>
            <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Penyerahan</h1>
            <p class="mt-1 text-sm text-ink-600">Serahkan surat izin yang sudah terbit & dokumentasikan penyerahan.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="rounded-full bg-nest-50 px-3 py-1.5 text-xs font-bold text-nest-700 ring-1 ring-nest-200">{{ $all_izin->total() }} izin</span>
            <div class="relative" id="helpPopoverWrap">
                <button type="button" id="helpPopoverBtn" aria-haspopup="true" aria-expanded="false"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-ink-200 bg-white text-ink-500 hover:border-nest-300 hover:text-nest-700 transition" title="Keterangan status">
                    <i class="fa-solid fa-circle-question"></i>
                </button>
                {{-- help popover --}}
                <div id="helpPopoverPanel"
                    class="hidden absolute right-0 top-[calc(100%+10px)] w-[720px] max-w-[94vw] rounded-2xl bg-white shadow-[0_24px_64px_rgba(15,23,42,0.20)] ring-1 ring-ink-200 overflow-hidden z-50 animate-fadeIn">
                    {{-- header --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-nest-700 to-nest-800 px-6 py-4 text-white">
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
                        <div class="relative flex items-start justify-between gap-3">
                            <div>
                                <p class="text-base font-extrabold tracking-tight"><i class="fa-solid fa-circle-info mr-2"></i>Panduan Status Izin</p>
                                <p class="mt-0.5 text-xs text-white/75">Dua tahap akhir pada alur penyerahan dokumen perizinan.</p>
                            </div>
                            <button type="button" id="helpPopoverClose" class="h-8 w-8 shrink-0 rounded-full bg-white/15 text-white grid place-items-center ring-1 ring-white/25 hover:bg-white/25 transition">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- steps: horizontal flow --}}
                    <div class="px-6 py-5 max-h-[62vh] overflow-y-auto">
                        <div class="grid gap-4 md:grid-cols-[1fr_auto_1fr] items-stretch">
                            {{-- step 1 --}}
                            <div class="flex flex-col rounded-xl bg-nest-50 p-4 ring-1 ring-nest-200">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="h-9 w-9 shrink-0 rounded-full bg-nest-700 text-white grid place-items-center text-sm font-extrabold">1</span>
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-widest text-amber-800">Proses</span>
                                </div>
                                <p class="mt-3 text-sm font-extrabold capitalize text-nest-800"><i class="fa-solid fa-pen-nib mr-1.5"></i>{{ $statusIzin::PENERBITAN }}</p>
                                <p class="mt-1.5 flex-1 text-xs leading-relaxed text-nest-800/80">
                                    Surat izin sedang melalui proses penandatanganan elektronik (TTE) oleh pejabat berwenang.
                                </p>
                                <div class="mt-3 flex items-start gap-2 rounded-lg bg-white/70 px-3 py-2 ring-1 ring-nest-200/70">
                                    <i class="fa-solid fa-bolt mt-0.5 text-[10px] text-amber-600"></i>
                                    <p class="text-[11px] font-semibold leading-snug text-ink-700">Aksi: perbarui status ke <b>Penyerahan</b> segera setelah tanda tangan selesai.</p>
                                </div>
                            </div>

                            {{-- connector --}}
                            <div class="hidden md:flex flex-col items-center justify-center px-1">
                                <span class="h-10 w-10 rounded-full bg-ink-900 text-white grid place-items-center shadow-lg"><i class="fa-solid fa-arrow-right text-sm"></i></span>
                            </div>

                            {{-- step 2 --}}
                            <div class="flex flex-col rounded-xl bg-emerald-50 p-4 ring-1 ring-emerald-200">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="h-9 w-9 shrink-0 rounded-full bg-emerald-600 text-white grid place-items-center text-sm font-extrabold">2</span>
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-widest text-emerald-800">Final</span>
                                </div>
                                <p class="mt-3 text-sm font-extrabold capitalize text-emerald-800"><i class="fa-solid fa-hand-holding-heart mr-1.5"></i>{{ $statusIzin::PENYERAHAN }}</p>
                                <p class="mt-1.5 flex-1 text-xs leading-relaxed text-emerald-800/80">
                                    Dokumen resmi siap diambil oleh pemohon di loket layanan MPP Bone Bolango.
                                </p>
                                <div class="mt-3 flex items-start gap-2 rounded-lg bg-white/70 px-3 py-2 ring-1 ring-emerald-200/70">
                                    <i class="fa-solid fa-bolt mt-0.5 text-[10px] text-emerald-600"></i>
                                    <p class="text-[11px] font-semibold leading-snug text-ink-700">Aksi: tandai <b>Selesai</b> setelah dokumen diterima pemohon agar tercatat arsip.</p>
                                </div>
                            </div>
                        </div>

                        {{-- note --}}
                        <div class="mt-4 flex items-center gap-2.5 rounded-xl bg-ink-50 px-4 py-3 ring-1 ring-ink-200">
                            <span class="h-8 w-8 shrink-0 rounded-lg bg-white grid place-items-center text-nest-700 ring-1 ring-ink-200"><i class="fa-solid fa-shield-halved text-sm"></i></span>
                            <p class="text-xs leading-relaxed text-ink-600">Setiap perubahan status tercatat otomatis dalam <b class="text-ink-800">jejak audit</b> sistem — lengkap dengan waktu dan pegawai yang memperbarui.</p>
                        </div>
                    </div>

                    {{-- footer --}}
                    <div class="flex items-center justify-between border-t border-ink-100 bg-ink-50/50 px-6 py-3">
                        <p class="text-xs font-semibold text-ink-500">Butuh bantuan lebih? Hubungi admin sistem.</p>
                        <p class="text-xs text-ink-400"><kbd class="rounded bg-white px-2 py-1 font-bold text-ink-600 ring-1 ring-ink-200">Esc</kbd> tutup</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- filter card --}}
    <div class="rounded-2xl bg-white p-4 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[180px_180px_1fr]">
            <div>
                <label for="start_date" class="text-xs font-bold tracking-wide text-ink-500 uppercase">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" wire:model.live.debounce.300ms='start_date'
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
            </div>
            <div>
                <label for="end_date" class="text-xs font-bold tracking-wide text-ink-500 uppercase">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" wire:model.live.debounce.300ms='end_date'
                    class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
            </div>
            <div>
                <label for="search" class="text-xs font-bold tracking-wide text-ink-500 uppercase">Cari</label>
                <div class="relative mt-1.5">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-sm"></i>
                    <input type="text" id="search" name="search" wire:model.live.debounce.400ms='search' placeholder="No pendaftaran, pemohon, jenis izin…"
                        class="w-full rounded-xl border border-ink-200 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                </div>
            </div>
        </div>
    </div>

    {{-- table --}}
    <div class="relative mt-4 overflow-hidden rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-ink-200 bg-ink-50/60">
                        <th class="px-5 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">No</th>
                        <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">No Pendaftaran</th>
                        <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">ID Pemohon</th>
                        <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Pemohon</th>
                        <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Jenis Izin</th>
                        <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Tanggal</th>
                        <th class="px-4 py-3.5 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Nomor Surat</th>
                        <th class="px-4 py-3.5 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($all_izin as $izin)
                        @php
                            $status = $izin->latestStatus;
                            $statusClasses = getStatusIzinClasses($status)
                        @endphp
                        <tr class="cursor-pointer data-izin transition hover:bg-nest-50/60" data-izin-id="{{ $izin->id }}" wire:click='previewDataIzin({{ $izin->id }})' id="modalRightShowBtn">
                            <td class="px-5 py-3.5 text-sm font-semibold text-ink-400">{{ $loop->iteration }}</td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-bold text-ink-900">{{ $izin->no_pendaftaran }}</td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm font-semibold text-ink-700">{{ $izin->pemohon->nomor_id }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <img src="{{ asset('storage/users/' . ($izin->pemohon->user ? $izin->pemohon->user->foto : 'profile.png')) }}" alt="{{ $izin->pemohon->nama_pemohon }}" class="h-8 w-8 shrink-0 rounded-full object-cover ring-1 ring-ink-200">
                                    <span class="text-sm font-bold text-ink-900">{{ str_capitalize($izin->pemohon->nama_pemohon, true) }}</span>
                                </div>
                            </td>
                            <td class="max-w-[220px] px-4 py-3.5 text-sm text-ink-600"><span class="line-clamp-2">{{ str_capitalize($izin->jenis_perizinan->nama, true) }}</span></td>
                            <td class="whitespace-nowrap px-4 py-3.5 text-sm text-ink-600">{{ $this->getTanggalPermohonan($izin) }}</td>
                            <td class="whitespace-nowrap px-4 py-3.5">
                                @if ($izin->no_surat)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200"><i class="fa-solid fa-file-circle-check text-[10px]"></i> Terbit</span>
                                    <p class="mt-0.5 text-[11px] font-semibold text-ink-600">{{ $izin->no_surat }}</p>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700 ring-1 ring-amber-200"><i class="fa-solid fa-hourglass-half text-[10px]"></i> Menunggu</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-bold ring-1 ring-ink-200 {{ $statusClasses['textClass'] }}" title="{{ $status->progres }}">
                                    <i class="{{ $statusClasses['bgClass'] }} h-1.5 w-1.5 rounded-full"></i> {{ Str::limit($status->nama, 16) }}
                                </span>
                                <p class="mt-0.5 text-[11px] text-ink-400">{{ $izin->updated_at->diffForHumans() }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5">
                                <div class="flex flex-col items-center justify-center gap-2 py-14 text-center">
                                    <div class="h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-regular fa-face-frown"></i></div>
                                    <p class="text-sm font-bold text-ink-900">Tidak ada data izin</p>
                                    <p class="text-sm text-ink-500">Coba ubah filter tanggal atau kata kunci pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div wire:loading wire:target='start_date, end_date, search'>
            <div class="absolute inset-0 z-10 grid place-items-center bg-white/75 backdrop-blur-[2px]">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-xl text-nest-700"></i>
                    <p class="text-xs font-bold text-ink-500">Memuat…</p>
                </div>
            </div>
        </div>

        <x-pagination-nestara :paginator="$all_izin" />
    </div>
</div>

        </div>
    </div>

@push('scripts')
    <script>
        // Help popover — event delegation agar tetap jalan setelah Livewire morph
        document.addEventListener('click', function (e) {
            const wrap = document.getElementById('helpPopoverWrap');
            const panel = document.getElementById('helpPopoverPanel');
            const btn = document.getElementById('helpPopoverBtn');
            if (!wrap || !panel || !btn) return;

            const openBtn = e.target.closest('#helpPopoverBtn');
            const closeBtn = e.target.closest('#helpPopoverClose');

            if (openBtn) {
                e.preventDefault();
                panel.classList.toggle('hidden');
                btn.setAttribute('aria-expanded', String(!panel.classList.contains('hidden')));
                return;
            }
            if (closeBtn) {
                panel.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                return;
            }
            if (!wrap.contains(e.target)) {
                panel.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            const panel = document.getElementById('helpPopoverPanel');
            const btn = document.getElementById('helpPopoverBtn');
            if (panel && !panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
                btn?.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
@endpush
