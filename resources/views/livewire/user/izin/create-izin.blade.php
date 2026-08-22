<div class="mx-auto max-w-[1080px] px-4 sm:px-6 lg:px-8 py-6">
    <md-linear-progress wire:loading wire:target='save, nextStep, prevStep, editExistingDataPemohon, saveChangeDataPemohon' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    {{-- header --}}
    <div class="mb-6">
        <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Ruang Pemohon • Pengajuan</p>
        <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Formulir Izin Baru</h1>
        <p class="mt-1 text-sm text-ink-600">Lengkapi tiga langkah berikut — data tersimpan aman setiap tahap.</p>
    </div>

    {{-- stepper --}}
    @php
        $steps = [
            ['Data Pemohon', self::FIRST_STEP],
            ['Izin & Persyaratan', self::SECOND_STEP],
            ['Konfirmasi', self::LAST_STEP],
        ];
        $currentIdx = collect($steps)->search(fn ($s) => $s[1] === $form_step);
    @endphp
    <ol class="mb-6 flex items-center gap-2">
        @foreach ($steps as $idx => [$label, $step])
            @php
                $done = $currentIdx !== false && $idx < $currentIdx;
                $active = $form_step === $step;
            @endphp
            <li class="flex items-center gap-2 {{ $loop->last ? '' : 'flex-1' }}">
                <span class="inline-flex items-center gap-2 rounded-full px-3.5 py-2 text-xs font-extrabold whitespace-nowrap transition
                    {{ $active ? 'bg-nest-700 text-white shadow-sm' : ($done ? 'bg-nest-50 text-nest-700 ring-1 ring-nest-200' : 'bg-white text-ink-400 ring-1 ring-ink-200') }}">
                    <span class="h-5 w-5 grid place-items-center rounded-full text-[10px] font-black
                        {{ $active ? 'bg-white/20 text-white' : ($done ? 'bg-nest-700 text-white' : 'bg-ink-100 text-ink-500') }}">
                        @if ($done)<i class="fa-solid fa-check"></i>@else{{ $idx + 1 }}@endif
                    </span>
                    {{ $label }}
                </span>
                @if (! $loop->last)
                    <span class="flex-1 h-0.5 rounded {{ $done || $active ? 'bg-nest-300' : 'bg-ink-200' }}"></span>
                @endif
            </li>
        @endforeach
    </ol>
    <form wire:submit.prevent='save'>
        @csrf
        <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
            <div class="min-w-0 space-y-4">
                @if ($user_already_has_data_pemohon && $form_step == self::FIRST_STEP && !$edit_existing_data_pemohon)
                    <div class="relative overflow-hidden rounded-2xl bg-white p-5 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)]">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <h4 class="font-display font-extrabold text-[16px] text-ink-900">{{ $form_data_pemohon->nama_pemohon }}</h4>
                                <p class="mt-1 text-sm font-semibold text-ink-700">
                                    <i class="fa-regular fa-id-card mr-1.5 text-ink-400"></i>{{ $form_data_pemohon->nomor_id }}
                                    <span class="ml-1 rounded-full bg-ink-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-ink-600">{{ $form_data_pemohon->sumber_identitas }}</span>
                                </p>
                                <p class="mt-0.5 text-sm font-semibold text-ink-700"><i class="fa-solid fa-phone mr-1.5 text-ink-400"></i>{{ $form_data_pemohon->no_telepon }}</p>
                                <p class="mt-0.5 flex items-start gap-1.5 text-xs text-ink-500"><i class="fa-solid fa-location-dot mt-0.5"></i>{{ $this->getAlamat() }}</p>
                            </div>
                            <button wire:click='editExistingDataPemohon' type="button"
                                class="inline-flex shrink-0 items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 text-xs font-bold text-ink-700 transition hover:border-nest-300 hover:bg-nest-50"
                                wire:loading.attr="disabled" wire:target='editExistingDataPemohon'>
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Ubah
                            </button>
                        </div>
                    </div>
                @endif
                <div class="card p-4 mb-0 {{ $user_already_has_data_pemohon && $form_step == self::FIRST_STEP && !$edit_existing_data_pemohon ? 'd-none' : '' }}">
                    @if ($form_step == self::FIRST_STEP)
                        @if (!$user_already_has_data_pemohon || $edit_existing_data_pemohon)
                            @include('livewire.dashboard.pendaftaran.partials.data-pemohon-form-field')
                            <div class="flex items-center justify-end gap-2 pt-2">
                                @if ($edit_existing_data_pemohon)
                                    <button type="button" wire:click='editExistingDataPemohon'
                                        class="inline-flex items-center gap-1.5 rounded-full border border-ink-200 bg-white px-4 py-2 text-xs font-bold text-ink-700 hover:bg-ink-50 transition"
                                        wire:loading.attr="disabled" wire:target='editExistingDataPemohon'>
                                        <span wire:loading.remove wire:target='editExistingDataPemohon'>Batal</span>
                                        <span wire:loading wire:target='editExistingDataPemohon'><i class="fa-solid fa-spinner fa-spin"></i></span>
                                    </button>
                                @endif
                                <button type="button" wire:click='saveChangeDataPemohon'
                                    class="inline-flex items-center gap-1.5 rounded-full bg-nest-700 px-5 py-2.5 text-xs font-extrabold text-white hover:bg-nest-800 transition disabled:opacity-50"
                                    wire:loading.attr="disabled" wire:target='saveChangeDataPemohon'>
                                    <span wire:loading.remove wire:target='saveChangeDataPemohon'><i class="fa-solid fa-floppy-disk text-[10px]"></i> Simpan Perubahan</span>
                                    <span wire:loading wire:target='saveChangeDataPemohon'><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan…</span>
                                </button>
                            </div>
                        @endif
                    @elseif ($form_step == self::SECOND_STEP)
                        <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200">
                            <h6 class="font-display text-sm font-extrabold uppercase tracking-wide text-ink-900">Data Permohonan</h6>
                            <label for="jenisPerizinanSearch" class="mt-4 block text-xs font-bold tracking-wide text-ink-500 uppercase">Jenis Izin</label>
                            <div class="relative mt-1.5" x-data="{ open: false }" @click.outside="open = false" @keydown.escape="open = false" @izin-selected.window="open = false">
                                <div class="flex items-center gap-2.5 rounded-xl border {{ $errors->has('data_izin_form.jenis_izin') ? 'border-red-300' : 'border-ink-200' }} bg-white px-3.5 py-2.5 transition">
                                    <i class="fa-solid fa-magnifying-glass text-xs text-ink-400"></i>
                                    <input type="text" id="jenisPerizinanSearch" wire:model.live.debounce.300ms='jenis_izin_search'
                                        placeholder="Cari jenis perizinan…" autocomplete="off"
                                        @focus="open = true"
                                        class="w-full bg-transparent text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:outline-none">
                                    <i class="fa-solid fa-spinner fa-spin text-nest-700" wire:loading wire:target='jenis_izin_search'></i>
                                    <button type="button" @click="open = !open" class="text-ink-400 hover:text-nest-700 transition" tabindex="-1">
                                        <i class="fa-solid fa-chevron-down text-xs transition-transform" :class="open && 'rotate-180'"></i>
                                    </button>
                                </div>

                                <div x-show="open" x-cloak x-transition.opacity.duration.150ms
                                    class="absolute z-20 mt-2 w-full overflow-hidden rounded-xl bg-white ring-1 ring-ink-200 shadow-[0_16px_40px_rgba(15,23,42,0.14)]">
                                    <ul class="max-h-64 overflow-y-auto p-1.5">
                                        @forelse ($filteredPerizinan as $perizinan)
                                            <li>
                                                <button type="button"
                                                    wire:click='selectJenisIzin("{{ $perizinan->nama }}")'
                                                    class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-left text-sm font-semibold transition {{ $data_izin_form->jenis_izin === $perizinan->nama ? 'bg-nest-50 text-nest-800' : 'text-ink-700 hover:bg-nest-50/60' }}">
                                                    <span class="min-w-0 truncate">{{ $perizinan->nama }}</span>
                                                    @if ($data_izin_form->jenis_izin === $perizinan->nama)
                                                        <i class="fa-solid fa-check shrink-0 text-xs text-nest-700"></i>
                                                    @endif
                                                </button>
                                            </li>
                                        @empty
                                            <li class="px-3 py-6 text-center text-sm font-semibold text-ink-400">Tidak ada jenis perizinan yang cocok.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                            @error('data_izin_form.jenis_izin')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                            <div class="relative mt-4">
                                @php
                                    $totalSyarat = $selectedPerizinan ? $selectedPerizinan->syarat_izin->count() : 0;
                                    $filledSyarat = collect($persyaratan)->filter(fn ($f) => filled($f))->count();
                                @endphp
                                <div class="flex items-center justify-between gap-3">
                                    <h6 class="text-xs font-extrabold uppercase tracking-widest text-ink-400">Persyaratan Izin</h6>
                                    @if ($totalSyarat > 0)
                                        <span class="rounded-full bg-nest-50 px-2.5 py-1 text-[11px] font-extrabold text-nest-700 ring-1 ring-nest-200">{{ $filledSyarat }}/{{ $totalSyarat }} terunggah</span>
                                    @endif
                                </div>

                                @if ($selectedPerizinan)
                                    <ol class="mt-3 space-y-3">
                                        @forelse ($selectedPerizinan->syarat_izin as $syarat)
                                            @php
                                                $type = $syarat->tipe == 'pdf' || $syarat->tipe == 'img' ? 'file' : 'text';
                                                $uploaded = isset($persyaratan[$syarat->id]) && filled($persyaratan[$syarat->id]);
                                                $hasError = $errors->has("persyaratan.{$syarat->id}");
                                                $icon = match ($syarat->tipe) {
                                                    'pdf' => 'fa-file-pdf',
                                                    'img' => 'fa-file-image',
                                                    default => 'fa-file-lines',
                                                };
                                                $badge = match ($syarat->tipe) {
                                                    'pdf' => 'bg-rose-50 text-rose-600',
                                                    'img' => 'bg-sky-50 text-sky-600',
                                                    default => 'bg-ink-100 text-ink-600',
                                                };
                                            @endphp
                                            <li class="rounded-xl border bg-white p-4 transition {{ $hasError ? 'border-red-300' : ($uploaded ? 'border-nest-300' : 'border-ink-200') }}"
                                                x-data="{ uploading: false, progress: 0, failed: false }"
                                                x-on:livewire-upload-start="uploading = true; failed = false"
                                                x-on:livewire-upload-finish="uploading = false; progress = 100"
                                                x-on:livewire-upload-cancel="uploading = false; progress = 0"
                                                x-on:livewire-upload-error="uploading = false; failed = true"
                                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                <div class="flex items-start gap-3">
                                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg {{ $badge }}">
                                                        <i class="fa-solid {{ $icon }}"></i>
                                                    </span>
                                                    <div class="min-w-0 flex-1">
                                                        <p class="text-sm font-bold leading-snug text-ink-900">{{ $syarat->nama }}</p>
                                                        <span class="mt-1 inline-flex items-center rounded-full {{ $badge }} px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide">{{ $syarat->tipe }}</span>
                                                    </div>
                                                    <span class="shrink-0 text-[11px] font-extrabold text-ink-300">#{{ $loop->iteration }}</span>
                                                </div>

                                                @if ($type === 'file')
                                                    <label for="syarat{{ $syarat->id }}"
                                                        class="mt-3 flex cursor-pointer flex-col items-center gap-1.5 rounded-xl border-2 border-dashed px-4 py-5 text-center transition hover:bg-nest-50/60 {{ $uploaded ? 'border-nest-300 bg-nest-50/40' : 'border-ink-200 hover:border-nest-400' }}"
                                                        :class="uploading && 'pointer-events-none opacity-60'">
                                                        @if ($uploaded)
                                                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                                                            <p class="max-w-full truncate text-xs font-bold text-ink-900">{{ $persyaratan[$syarat->id]->getClientOriginalName() }}</p>
                                                            <span class="text-[11px] font-semibold text-ink-500 underline underline-offset-2">Klik untuk mengganti berkas</span>
                                                        @else
                                                            <i class="fa-solid fa-cloud-arrow-up text-lg text-ink-400"></i>
                                                            <p class="text-xs font-semibold text-ink-600">Klik untuk unggah berkas <span class="font-extrabold uppercase">{{ $syarat->tipe }}</span></p>
                                                        @endif
                                                    </label>
                                                    <input type="file" id="syarat{{ $syarat->id }}" class="sr-only" wire:model='persyaratan.{{ $syarat->id }}'>

                                                    <div x-show="uploading" x-cloak class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-ink-100">
                                                        <div class="h-full rounded-full bg-nest-700 transition-all duration-150" x-bind:style="`width: ${progress}%`"></div>
                                                    </div>
                                                    <p x-show="uploading" x-cloak class="mt-1.5 text-[11px] font-bold text-nest-700" x-text="`Mengunggah… ${Math.round(progress)}%`"></p>
                                                    <p x-show="failed" x-cloak class="mt-1.5 flex items-center gap-1.5 text-[11px] font-bold text-red-600"><i class="fa-solid fa-triangle-exclamation"></i> Unggah gagal, silakan coba lagi.</p>
                                                @else
                                                    <input type="text" id="syarat{{ $syarat->id }}" wire:model='persyaratan.{{ $syarat->id }}'
                                                        class="mt-3 w-full rounded-xl border border-ink-200 bg-white px-4 py-2.5 text-sm font-medium text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
                                                @endif

                                                @if ($syarat->tipe == 'img' && $uploaded)
                                                    <div class="mt-3 w-48 overflow-hidden rounded-xl ring-1 ring-ink-200">
                                                        <img src="{{ $persyaratan[$syarat->id]->temporaryUrl() }}" alt="Pratinjau {{ $syarat->nama }}" class="w-full object-cover">
                                                    </div>
                                                @endif

                                                @error("persyaratan.{$syarat->id}")
                                                    <p class="mt-2 flex items-center gap-1.5 text-xs font-semibold text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ str_capitalize($message, true) }}</p>
                                                @enderror
                                            </li>
                                        @empty
                                            <li class="rounded-xl border border-dashed border-ink-200 p-8 text-center">
                                                <div class="mx-auto grid h-11 w-11 place-items-center rounded-xl bg-emerald-50 text-emerald-600"><i class="fa-solid fa-check"></i></div>
                                                <p class="mt-2.5 text-sm font-bold text-ink-900">Tidak ada persyaratan</p>
                                                <p class="text-xs text-ink-500">Silakan lanjut ke langkah berikutnya.</p>
                                            </li>
                                        @endforelse
                                    </ol>
                                @else
                                    <div class="mt-3 rounded-xl border border-dashed border-ink-200 p-8 text-center">
                                        <div class="mx-auto grid h-11 w-11 place-items-center rounded-xl bg-ink-50 text-ink-400"><i class="fa-solid fa-hand-pointer"></i></div>
                                        <p class="mt-2.5 text-sm font-bold text-ink-900">Pilih jenis izin terlebih dahulu</p>
                                        <p class="text-xs text-ink-500">Daftar persyaratan akan muncul otomatis di sini.</p>
                                    </div>
                                @endif

                                <div wire:loading wire:target='data_izin_form.jenis_izin'>
                                    <div class="absolute inset-0 z-10 grid place-items-center rounded-xl bg-white/75 backdrop-blur-[2px]">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fa-solid fa-spinner fa-spin text-xl text-nest-700"></i>
                                            <p class="text-xs font-bold text-ink-500">Memuat persyaratan…</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($form_step == self::LAST_STEP)
                        @php
                            $fileIcon = fn ($tipe) => match ($tipe) {
                                'pdf' => 'fa-file-pdf',
                                'img' => 'fa-file-image',
                                default => 'fa-file-lines',
                            };
                            $fileBadge = fn ($tipe) => match ($tipe) {
                                'pdf' => 'bg-rose-50 text-rose-600',
                                'img' => 'bg-sky-50 text-sky-600',
                                default => 'bg-ink-100 text-ink-600',
                            };
                        @endphp

                        {{-- data pemohon --}}
                        <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200">
                            <h3 class="text-[11px] font-extrabold uppercase tracking-widest text-ink-400">Data Pemohon</h3>
                            <div class="mt-1 divide-y divide-ink-100">
                                <div class="grid gap-1 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                                    <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Nama</p>
                                    <p class="text-sm font-bold capitalize text-ink-900">{{ $form_data_pemohon->nama_pemohon }}</p>
                                </div>
                                <div class="grid gap-1 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                                    <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Nomor Identitas</p>
                                    <p class="flex items-center gap-2 text-sm font-semibold text-ink-900">{{ $form_data_pemohon->nomor_id }}
                                        <span class="rounded-full bg-ink-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-ink-600">{{ $form_data_pemohon->sumber_identitas }}</span>
                                    </p>
                                </div>
                                <div class="grid gap-1 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                                    <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Nomor Telepon</p>
                                    <p class="text-sm font-semibold text-ink-900">{{ $form_data_pemohon->no_telepon }}</p>
                                </div>
                                <div class="grid gap-1 py-3.5 sm:grid-cols-[200px_1fr] sm:items-start">
                                    <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Alamat</p>
                                    <p class="text-sm leading-relaxed text-ink-700">{{ $this->getAlamat() }}</p>
                                </div>
                                @if ($form_data_pemohon->alamat_luar_negeri)
                                    <div class="grid gap-1 py-3.5 sm:grid-cols-[200px_1fr] sm:items-start">
                                        <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Alamat Luar Negeri</p>
                                        <p class="text-sm leading-relaxed text-ink-700">{{ $form_data_pemohon->alamat_luar_negeri }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- data izin --}}
                        <div class="mt-4 rounded-2xl bg-white p-5 ring-1 ring-ink-200">
                            <h3 class="text-[11px] font-extrabold uppercase tracking-widest text-ink-400">Data Izin</h3>
                            <div class="mt-1 divide-y divide-ink-100">
                                <div class="grid gap-1 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                                    <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Jenis Izin</p>
                                    <p class="inline-flex w-fit items-center rounded-full bg-nest-50 px-3 py-1 text-sm font-extrabold text-nest-800 ring-1 ring-nest-200">{{ $data_izin_form->jenis_izin }}</p>
                                </div>
                            </div>

                            <p class="mt-4 text-xs font-extrabold uppercase tracking-widest text-ink-400">Persyaratan Terunggah</p>
                            @if (count($persyaratan) > 0)
                                <ul class="mt-2 space-y-2">
                                    @foreach ($persyaratan as $syarat_id => $syarat_izin)
                                        @php($syarat_model = $this->getSyaratIzinService()->find($syarat_id))
                                        <li class="flex items-center gap-3 rounded-xl border border-ink-200 p-3 transition hover:border-nest-300">
                                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg {{ $fileBadge($syarat_model->tipe) }}">
                                                <i class="fa-solid {{ $fileIcon($syarat_model->tipe) }}"></i>
                                            </span>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate text-sm font-bold text-ink-900">{{ $syarat_model->nama }}</p>
                                                <p class="truncate text-xs text-ink-500">{{ $syarat_izin->getClientOriginalName() }}</p>
                                                @if ($syarat_model->tipe == 'img')
                                                    <img src="{{ $syarat_izin->temporaryUrl() }}" alt="Pratinjau {{ $syarat_model->nama }}" class="mt-2 w-40 rounded-lg ring-1 ring-ink-200">
                                                @endif
                                            </div>
                                            <a href="{{ $syarat_izin->temporaryUrl() }}" target="_blank" title="Lihat berkas"
                                                class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white text-ink-500 ring-1 ring-ink-200 transition hover:bg-nest-50 hover:text-nest-700">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mt-2 rounded-xl border border-dashed border-ink-200 p-6 text-center text-sm font-semibold text-ink-400">Tidak ada berkas persyaratan.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <aside class="h-fit lg:sticky lg:top-[88px]">
                <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] space-y-2">
                    @if ($form_step == self::FIRST_STEP)
                        <button type="button" wire:click='nextStep' class="btn-next-step w-full inline-flex items-center justify-center gap-2 rounded-full bg-nest-700 px-5 py-3 text-sm font-extrabold text-white hover:bg-nest-800 transition">Berikutnya <i class="fa-solid fa-arrow-right"></i></button>
                    @elseif ($form_step == self::SECOND_STEP)
                        <button type="button" wire:click='nextStep' class="btn-next-step w-full inline-flex items-center justify-center gap-2 rounded-full bg-nest-700 px-5 py-3 text-sm font-extrabold text-white hover:bg-nest-800 transition">Berikutnya <i class="fa-solid fa-arrow-right"></i></button>
                        <button class="w-full inline-flex items-center justify-center gap-2 rounded-full border border-ink-200 bg-white px-5 py-3 text-sm font-bold text-ink-700 hover:bg-ink-50 transition" type="button" wire:click='prevStep'><i class="fa-solid fa-arrow-left"></i> Sebelumnya</button>
                    @elseif ($form_step == self::LAST_STEP)
                        <h6 class="text-md">Info</h6>
                        <p class="text-xs text-secondary">
                            Pastikan semua data yang Anda masukkan sudah benar sebelum menekan tombol "Daftar". Setelah menekan tombol "Daftar", data akan diproses dan disimpan dalam sistem.  
                        </p>
                        <hr class="mb-2 mt-0">
                        @if ($errors->any())
                            <div class="rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                                <p class="flex items-center gap-1.5 text-xs font-extrabold uppercase tracking-wide text-red-600"><i class="fa-solid fa-circle-exclamation"></i> Periksa kembali</p>
                                <ul class="mt-1.5 list-inside list-disc space-y-0.5 text-xs font-semibold text-red-600">
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-nest-700 px-5 py-3 text-sm font-extrabold text-white shadow-[0_10px_24px_rgba(15,118,110,0.28)] hover:bg-nest-800 transition disabled:opacity-50" type="submit"
                            wire:loading.attr="disabled" wire:target='save'>
                            <span wire:loading.remove wire:target='save'><i class="fa-solid fa-paper-plane"></i> Daftarkan Permohonan</span>
                            <span wire:loading wire:target='save'><i class="fa-solid fa-spinner fa-spin"></i> Memproses…</span>
                        </button>
                        <button class="w-full inline-flex items-center justify-center gap-2 rounded-full border border-ink-200 bg-white px-5 py-3 text-sm font-bold text-ink-700 hover:bg-ink-50 transition" type="button" wire:click='prevStep'><i class="fa-solid fa-arrow-left"></i> Sebelumnya</button>
                    @endif
                </div>
            </aside>
        </div>
    </form>
</div>
@push('scripts')
    <script>
        document.addEventListener('click', function (e) {
            if (e.target.closest('.btn-next-step')) window.scrollTo(0, 0)
        })
    </script>
@endpush