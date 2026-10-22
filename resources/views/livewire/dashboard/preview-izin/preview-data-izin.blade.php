<div wire:key="preview-data-izin-{{ $preview_izin_id ?: 'empty' }}">
    @if ($preview_page)
        <div wire:key="preview-page-{{ $preview_page }}-{{ $preview_izin_id ?: 'empty' }}">
            @livewire($preview_page, ['preview_izin_id' => $preview_izin_id], key($preview_page . '-' . $preview_izin_id))
        </div>
    @else
        <md-linear-progress wire:loading wire:target='back, saveAction, saveEntri, saveTinjauanLapangan' class="fixed top-0 w-full left-0" id="pendaftaran-progress" indeterminate style="--md-linear-progress-active-indicator-color: var(--color-primary-disable); z-index: 9999"></md-linear-progress>

        <div class="grid w-full grid-cols-1 gap-x-6 gap-y-6 lg:grid-cols-12">
            @if ($this->canAccess())
                <section class="col-span-full min-w-0 lg:col-span-4" aria-labelledby="pemohon-heading">
                    <div class="h-full rounded-2xl border border-ink-200 bg-white p-5 shadow-sm">
                        <div class="mb-5 flex items-center gap-3 border-b border-ink-100 pb-4">
                            @php($pemohon = $preview_izin->pemohon)
                            @php($foto = $pemohon?->user?->foto)
                            @if ($foto)
                                <img src="{{ asset('storage/users/' . $foto) }}" alt="Foto {{ str_capitalize($pemohon?->nama_pemohon ?? 'Pemohon', true) }}" class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-ink-200">
                            @else
                                <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-ink-100 text-ink-500" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                            @endif
                            <div class="min-w-0">
                                <p class="text-[11px] font-extrabold uppercase tracking-widest text-nest-700">Pemohon</p>
                                <h2 id="pemohon-heading" class="mt-1 break-words text-lg font-extrabold leading-tight text-ink-900">{{ str_capitalize($preview_izin->pemohon->nama_pemohon, true) }}</h2>
                            </div>
                        </div>
                        <dl class="space-y-4">
                            <div class="flex gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-nest-50 text-nest-700"><i class="fa-regular fa-id-card text-sm"></i></span><div class="min-w-0"><dt class="text-xs font-semibold text-ink-500">Nomor identitas</dt><dd class="break-words text-sm font-bold text-ink-900">{{ $preview_izin->pemohon->nomor_id }} <span class="font-normal uppercase text-ink-500">({{ $preview_izin->pemohon->sumber_identitas }})</span></dd></div></div>
                            <div class="flex gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-nest-50 text-nest-700"><i class="fa-solid fa-phone text-sm"></i></span><div><dt class="text-xs font-semibold text-ink-500">Nomor telepon</dt><dd class="text-sm font-bold text-ink-900">{{ $preview_izin->pemohon->no_telepon }}</dd></div></div>
                            <div class="flex gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-nest-50 text-nest-700"><i class="fa-solid fa-location-dot text-sm"></i></span><div class="min-w-0"><dt class="text-xs font-semibold text-ink-500">Alamat</dt><dd class="break-words text-sm leading-relaxed text-ink-800">{{ $this->getAlamat($preview_izin->pemohon->id) }}</dd></div></div>
                        </dl>
                    </div>
                </section>
                <section class="col-span-full min-w-0 mt-6 lg:col-span-8 lg:mt-0" aria-labelledby="izin-heading">
                    <div class="h-full rounded-2xl border border-ink-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col gap-2 border-b border-ink-100 pb-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0"><p class="text-[11px] font-extrabold uppercase tracking-widest text-nest-700">Informasi izin</p><h2 id="izin-heading" class="mt-1 break-words text-xl font-extrabold leading-tight text-ink-900">{{ str_capitalize($preview_izin->jenis_perizinan->nama, true) }}</h2></div>
                        </div>
                        <dl class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-xl bg-ink-50 p-3"><dt class="text-xs text-ink-500"><i class="fa-solid fa-layer-group mr-1 text-nest-700"></i>Kelompok izin</dt><dd class="mt-1 break-words text-sm font-bold text-ink-900">{{ $preview_izin->jenis_perizinan->kelompok_izin }}</dd></div>
                            <div class="rounded-xl bg-ink-50 p-3"><dt class="text-xs text-ink-500"><i class="fa-solid fa-hourglass-half mr-1 text-nest-700"></i>Durasi pengerjaan</dt><dd class="mt-1 break-words text-sm font-bold text-ink-900">{{ $preview_izin->jenis_perizinan->durasi }}</dd></div>
                            <div class="rounded-xl bg-ink-50 p-3"><dt class="text-xs text-ink-500"><i class="fa-regular fa-calendar-check mr-1 text-nest-700"></i>Masa berlaku</dt><dd class="mt-1 break-words text-sm font-bold text-ink-900">{{ $preview_izin->jenis_perizinan->masa_berlaku }}</dd></div>
                        </dl>
                        <div class="mt-6">
                            <h3 class="mb-3 flex items-center gap-2 text-sm font-extrabold text-ink-900"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-nest-50 text-nest-700"><i class="fa-solid fa-list-check text-xs"></i></span>Status izin</h3>
                            <div class="border-l-2 border-nest-100 pl-4">
                                @foreach ($preview_izin->status_izin as $status)
                                    <div class="relative pb-3 last:pb-0"><span class="absolute -left-[21px] top-1 h-2.5 w-2.5 rounded-full bg-nest-600 ring-4 ring-white"></span>{!! renderStatusIzinWithIconAndDetails($status, true, true, true) !!}</div>
                                @endforeach
                            </div>
                        </div>

                        @if($this->canAccess() && $preview_izin->lokasi_izin !== null && $last_izin_status?->nama !== App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN)
                            <div class="mt-4">
                                <h6 class="font-bold">
                                    <i class="fa-regular fa-circle-right mr-1"></i>
                                    Detail permohonan
                                </h6>
                                <div class="pl-4">
                                    <span class="text-xs text-ink-500">
                                        <i class="fa-solid fa-building mr-1"></i>
                                        Lokasi Izin
                                    </span>
                                    <p class="font-semibold text-ink-900">{{ $preview_izin->lokasi_izin }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <h6 class="font-bold">
                                    <i class="fa-regular fa-circle-right mr-1"></i>
                                    Persyaratan
                                </h6>
                                <div class="px-4">
                                    @include('livewire.dashboard.preview-izin.preview-izin-persyaratan-show')
                                </div>
                            </div>
                        @endif
                        @role(FRONT_OFFICE_ROLE)
                            @if ($preview_izin->lokasi_izin === null || $last_izin_status?->nama == App\utils\enums\StatusIzinEnum::REVISI_PERSYARATAN)
                                <div class="mt-4">
                                    <ul class="flex gap-2 border-b border-ink-200">
                                        <li>
                                            <button type="button" role="tab" aria-controls="persyaratan-tab" aria-selected="{{ $active_tab != self::TINDAKAN ? 'true' : 'false' }}" wire:click="setActiveTab('persyaratan')" class="border-b-2 px-3 py-2 text-sm font-semibold @if($active_tab != self::TINDAKAN)border-nest-700 text-nest-700 @else border-transparent text-ink-500 @endif">
                                                Persyaratan
                                            </button>
                                        </li>
                                        <li>
                                            <button type="button" role="tab" aria-controls="tindakan-tab" aria-selected="{{ $active_tab == self::TINDAKAN ? 'true' : 'false' }}" wire:click="setActiveTab('tindakan')" class="border-b-2 px-3 py-2 text-sm font-semibold @if($active_tab == self::TINDAKAN)border-nest-700 text-nest-700 @else border-transparent text-ink-500 @endif">
                                                Tindakan
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="rounded-b-xl border border-t-0 p-4">
                                        <div id="persyaratan-tab" role="tabpanel" @if($active_tab == self::TINDAKAN)hidden @endif>
                                            <p class="mb-3 mt-3 text-ink-500">Persyaratan</p>
                                            @include('livewire.dashboard.preview-izin.preview-izin-persyaratan-show')
                                        </div>
                                        <div id="tindakan-tab" role="tabpanel" @if($active_tab != self::TINDAKAN)hidden @endif>
                                            @if ($this->canProcess())
                                                <form wire:submit.prevent='saveAction'>
                                                    <label for="lokasi_izin" class="mb-1 block text-sm font-semibold text-ink-700">Lokasi Izin</label>
                                                    <div class=" mb-3">
                                                        <input type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('lokasi_izin')is-invalid @enderror" id="lokasi_izin" wire:model.live='lokasi_izin'>
                                                    </div>
                                                    @error('lokasi_izin')
                                                        <div class="mb-3 text-red-600 text-sm">{{ $message }}</div>
                                                    @enderror
                                                    <label for="keterangan" class="mb-1 block text-sm font-semibold text-ink-700">Keterangan</label>
                                                    <div class=" @error('keterangan')mb-1 @else mb-3 @enderror">
                                                        <textarea rows="3" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('keterangan')is-invalid @enderror" id="keterangan" wire:model.live='keterangan'></textarea>
                                                    </div>
                                                    @error('keterangan')
                                                        <div class="mb-3 text-red-600 text-sm">{{ $message }}</div>
                                                    @enderror
                                                    <div class="flex items-center gap-3 mb-3">
                                                        <div class="flex items-center gap-2">
                                                            <input type="radio" class="h-4 w-4 accent-nest-700" name="action_confirm" id="permohonan-selesai" wire:model.live='action_confirm' value="permohonan-selesai">
                                                            <label for="permohonan-selesai" class="">Permohonan Selesai</label>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <input type="radio" class="h-4 w-4 accent-nest-700" name="action_confirm" id="revisi-permohonan" wire:model.live='action_confirm' value="revisi-permohonan">
                                                            <label for="revisi-permohonan" class="">Revisi Permohonan</label>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-start gap-3">
                                                        <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white{{ !$lokasi_izin || !$action_confirm || ($action_confirm == 'revisi-permohonan' && !$keterangan) ? ' disabled' : '' }}" {{ !$lokasi_izin || !$action_confirm || ($action_confirm == 'revisi-permohonan' && !$keterangan) ? 'disabled' : '' }} type="submit">Simpan</button>
                                                    </div>
                                                </form>
                                            @else
                                                <div class="rounded-xl border border-ink-200 bg-ink-50 p-3">
                                                    <p class="mt-3 text-ink-500">
                                                        <i class="fa-solid fa-circle-info mr-1"></i>
                                                        Proses perbaikan persyaratan oleh pemohon
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endrole
                    </div>
                </div>

                @if ($this->canAccessSavedEntriData())
                    <div class="col-span-full min-w-0 mt-6">
                        <div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
                            <div class="p-5">
                                <div class="flex justify-between items-center">
                                    <h6 class="font-bold mb-4">
                                    <i class="fa-regular fa-circle-right mr-1"></i>
                                        Entri Data
                                    </h6>
                                    @if ($this->alreadyEntriData())
                                        <div class="flex items-center gap-2">
                                            <button class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition" wire:click='reEditEntriData' wire:loading.remove wire:target='reEditEntriData'>
                                                <span wire:loading.remove wire:target='reEditEntriData'>Edit Entri Data</span>
                                                <span class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" role="status" aria-label="Memuat" wire:loading wire:target='reEditEntriData'>
                                                </span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                @if ($last_izin_status->keterangan == App\utils\enums\BackOfficeStatusEnum::SUDAH_ENTRI && $last_izin_status->keterangan != App\utils\enums\BackOfficeStatusEnum::ENTRI_TINJAUAN_LAPANGAN)
                                    @include('livewire.dashboard.preview-izin.button-edit-entri-data')
                                @endif
                                @if ($is_entri_changed && !$preview_izin->ambil_entri_lama)
                                    @include('livewire.dashboard.preview-izin.button-entri-has-changed')
                                @endif
                                @include('livewire.dashboard.preview-izin.saved-entri-data')
                            </div>
                        </div>
                    </div>
                @endif

                @role(BACK_OFFICE_ROLE)
                    <div class="col-span-full min-w-0 mt-6 grid">
                        @if ($this->hasEntriDataForm())
                            @if ($this->isFormNotReady())
                                <div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
                                    @include('livewire.dashboard.preview-izin.button-entri-data')
                                </div>
                            @else
                                @if ($this->isFormNotEntered())
                                    @if ($this->isFormFillAuthorized())
                                        <div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
                                            <div class="p-5">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h6 class="font-bold mb-1">
                                                        <i class="fa-regular fa-circle-right mr-1"></i>
                                                        Entri Data
                                                    </h6>
                                                    @if(get_pengaturan('CLIENT_TOKEN') != null)
                                                        <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs bg-sky-600 text-white capitalize" type="button" wire:click='automaticFill' wire:loading.attr="disabled" wire:target="automaticFill" aria-describedby="ocr-prefill-status">
                                                            <span wire:loading.remove wire:target='automaticFill'>Isi dari OCR</span>
                                                            <span class="animate-spin h-4 w-4 rounded-full border-2 border-white border-t-transparent" role="status" wire:loading wire:target='automaticFill'></span>
                                                            <span class="sr-only" wire:loading wire:target="automaticFill">Membaca dokumen...</span>
                                                        </button>
                                                        <div id="ocr-prefill-status" class="mt-1 text-xs text-ink-500" role="status" aria-live="polite">
                                                            <span wire:loading wire:target="automaticFill">Membaca dokumen. Nilai manual tetap aman.</span>
                                                            @if (in_array(($ocr_prefill_status ?? null), ['success', 'partial']))<span wire:loading.remove wire:target="automaticFill">Kandidat OCR: {{ $ocr_prefill_mapped_count ?? 0 }} terpetakan, {{ $ocr_prefill_unmapped_count ?? 0 }} belum terpetakan. Periksa sebelum simpan.</span>@endif
                                                            @if (($ocr_prefill_status ?? null) === 'partial')<span class="text-warning" wire:loading.remove wire:target="automaticFill">Sebagian data OCR belum punya field tujuan.</span>@endif
                                                            @if (($ocr_prefill_status ?? null) === 'empty')<span wire:loading.remove wire:target="automaticFill">Kandidat OCR tidak ditemukan.</span>@endif
                                                            @if (($ocr_prefill_status ?? null) === 'error')<span class="text-red-600" wire:loading.remove wire:target="automaticFill">OCR gagal. Coba lagi.</span>@endif
                                                        </div>
                                                    @endif
                                                </div>
                                                @include('livewire.dashboard.preview-izin.form-entri-data')
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-2xl border border-ink-200 bg-white shadow-sm">
                                            @include('livewire.dashboard.preview-izin.filling-entri-form-not-authorized-alert')
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @else
                            <div class="rounded-xl border border-ink-200 bg-ink-50 p-3">
                                <p class="mt-3 text-ink-500">
                                    <i class="fa-solid fa-circle-info mr-1"></i>
                                    Tidak ada formulir data entri untuk perizinan ini!
                                </p>
                            </div>
                        @endif

                        @if ($this->tinjauLapangan())
                            <div class="rounded-2xl border border-ink-200 bg-white shadow-sm" id="timTeknis">
                                <div class="p-5">
                                    @include('livewire.dashboard.back-office.partials.penjadwalan-tim-form')
                                </div>
                            </div>
                        @endif

                    </div>
                @endrole
            @else
                <div class="absolute inset-0 flex items-center justify-center">
                    {{ $permission_message }}
                </div>
            @endif
            {{-- Keep modal inside stable root; conditional content avoids extra Livewire root. --}}
            <div wire:key="preview-document-modal-{{ $preview_izin_id ?: 'empty' }}" class="contents">
                @include('livewire.dashboard.preview-izin.modal-show-document')
            </div>
        </div>
    @endif
</div>
