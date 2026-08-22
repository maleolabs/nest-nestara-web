@push('breadcrumb-item')
    <li class="text-xs font-semibold text-ink-600">/ Pengaturan</li>
@endpush
<div class="mx-auto max-w-[880px] space-y-6">
    <md-linear-progress wire:loading wire:target='selectTab' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    {{-- header --}}
    <div>
        <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Konfigurasi Sistem</p>
        <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Pengaturan</h1>
        <p class="mt-1 text-sm text-ink-600">Kelola identitas surat resmi dan integrasi API OCR.</p>
    </div>

    {{-- kop & info surat --}}
    <section class="rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden">
        <div class="flex items-center gap-3 border-b border-ink-100 px-6 py-4">
            <span class="h-9 w-9 rounded-xl bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200"><i class="fa-solid fa-file-lines text-sm"></i></span>
            <div>
                <h3 class="text-sm font-extrabold text-ink-900">Pengaturan Surat</h3>
                <p class="text-xs text-ink-500">Kop surat & info penetapan dokumen resmi.</p>
            </div>
        </div>
        <div class="p-6 space-y-8">
            {{-- kop --}}
            <div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-heading text-xs text-ink-400"></i>
                    <p class="text-sm font-bold text-ink-900">Kop Surat</p>
                </div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nama Pemerintah</label>
                        <input type="text" wire:model='kop_surat_form.nama_pemerintah' placeholder="Pemerintah Kabupaten Bone Bolango"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        @error('kop_surat_form.nama_pemerintah')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nama Dinas</label>
                        <input type="text" wire:model='kop_surat_form.nama_dinas' placeholder="Dinas Penanaman Modal & PTSP"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        @error('kop_surat_form.nama_dinas')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Alamat</label>
                        <input type="text" wire:model='kop_surat_form.alamat' placeholder="Alamat lengkap dinas"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        @error('kop_surat_form.alamat')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Website</label>
                        <input type="text" wire:model='kop_surat_form.website' placeholder="https://bonebolango.go.id"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        @error('kop_surat_form.website')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="button" wire:click='saveKopSurat' wire:loading.class='opacity-50 pointer-events-none' wire:target='saveKopSurat'
                    class="mt-4 inline-flex items-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-ink-800 transition">
                    <span wire:loading.remove wire:target='saveKopSurat'><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Kop Surat</span>
                    <span class="hidden" wire:loading wire:target='saveKopSurat'><i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan…</span>
                </button>
            </div>

            <div class="border-t border-dashed border-ink-200"></div>

            {{-- info surat --}}
            <div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-stamp text-xs text-ink-400"></i>
                    <p class="text-sm font-bold text-ink-900">Info Surat</p>
                </div>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nomor Surat Terakhir</label>
                        <input type="number" wire:model='nomor_surat' placeholder="Contoh: 128"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        @error('nomor_surat')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Tempat Penetapan</label>
                        <input type="text" wire:model='tempat_penetapan' placeholder="Contoh: Suwawa"
                            class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        @error('tempat_penetapan')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="button" wire:click='saveInfoSurat' wire:loading.class='opacity-50 pointer-events-none' wire:target='saveInfoSurat'
                    class="mt-4 inline-flex items-center gap-2 rounded-full bg-ink-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-ink-800 transition">
                    <span wire:loading.remove wire:target='saveInfoSurat'><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Info Surat</span>
                    <span class="hidden" wire:loading wire:target='saveInfoSurat'><i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan…</span>
                </button>
            </div>
        </div>
    </section>

    {{-- OCR --}}
    @if ($ocr_setting)
        <section class="rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden">
            <div class="flex items-center justify-between border-b border-ink-100 px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="h-9 w-9 rounded-xl bg-sky-50 text-sky-700 grid place-items-center ring-1 ring-sky-200"><i class="fa-solid fa-cloud-arrow-up text-sm"></i></span>
                    <div>
                        <h3 class="text-sm font-extrabold text-ink-900">Integrasi API OCR</h3>
                        <p class="text-xs text-ink-500">Autentikasi & endpoint layanan OCR untuk baca dokumen otomatis.</p>
                    </div>
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700 ring-1 ring-emerald-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktif
                </span>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid gap-4 sm:grid-cols-[minmax(0,280px)_1fr] sm:items-start">
                    <div>
                        <p class="text-sm font-bold text-ink-900">API Token</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-ink-500">API Key untuk autentikasi permintaan ke layanan OCR. Jangan bagikan ke pihak lain.</p>
                    </div>
                    <div>
                        <div class="relative">
                            <i class="fa-solid fa-key pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-xs"></i>
                            <input type="password" wire:model='api_key' placeholder="Masukkan API Key"
                                class="w-full rounded-xl border border-ink-200 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        </div>
                    </div>
                </div>
                <div class="border-t border-dashed border-ink-200"></div>
                <div class="grid gap-4 sm:grid-cols-[minmax(0,280px)_1fr] sm:items-start">
                    <div>
                        <p class="text-sm font-bold text-ink-900">Base URL API</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-ink-500">URL dasar layanan OCR — sesuaikan dengan lingkungan (produksi/pengujian).</p>
                    </div>
                    <div>
                        <div class="relative">
                            <i class="fa-solid fa-link pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-xs"></i>
                            <input type="text" wire:model='base_url' placeholder="https://api.ocr-system.com/v1"
                                class="w-full rounded-xl border border-ink-200 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                        </div>
                    </div>
                </div>

                @if($ocr_config_saved)
                    <div class="flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-3 ring-1 ring-emerald-200 text-sm font-semibold text-emerald-700">
                        <i class="fa-solid fa-circle-check"></i> Berhasil menyimpan konfigurasi API OCR.
                    </div>
                @endif

                <button type="button" wire:click='saveOcrApiConfig' wire:loading.class='opacity-50 pointer-events-none' wire:target='saveOcrApiConfig'
                    class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-bold text-white shadow-[0_8px_20px_rgba(15,118,110,0.25)] hover:bg-nest-800 transition">
                    <span wire:loading.remove wire:target='saveOcrApiConfig'><i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Konfigurasi OCR</span>
                    <span class="hidden" wire:loading wire:target='saveOcrApiConfig'><i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan…</span>
                </button>
            </div>
        </section>
    @endif
</div>
