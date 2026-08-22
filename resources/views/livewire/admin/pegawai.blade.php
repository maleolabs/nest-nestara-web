@push('breadcrumb-item')
    <li class="text-xs font-semibold text-ink-600">/ Pegawai</li>
@endpush
<div>
<div class="grid gap-6 lg:grid-cols-[280px_1fr]">
    <md-linear-progress wire:loading wire:target='selectTab' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    {{-- tab nav --}}
    <aside class="h-fit rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden lg:sticky lg:top-[88px]">
        <div class="border-b border-ink-100 px-5 py-4">
            <h3 class="text-sm font-extrabold text-ink-900">Manajemen Pegawai</h3>
            <p class="text-xs text-ink-500">Pilih kategori untuk dikelola</p>
        </div>
        <nav class="p-2 space-y-1">
            <button type="button" wire:click="selectTab('{{ self::FRONT_OFFICE_TAB }}')" onclick="switchTab('{{ self::FRONT_OFFICE_TAB }}')"
                class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $current_tab == self::FRONT_OFFICE_TAB ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
                <span class="h-8 w-8 grid place-items-center rounded-lg {{ $current_tab == self::FRONT_OFFICE_TAB ? 'bg-white/15' : 'bg-sky-50 text-sky-600' }}"><i class="fa-solid fa-headset text-xs"></i></span>
                Front Office
            </button>
            <button type="button" wire:click="selectTab('{{ self::BACK_OFFICE_TAB }}')" onclick="switchTab('{{ self::BACK_OFFICE_TAB }}')"
                class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $current_tab == self::BACK_OFFICE_TAB ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
                <span class="h-8 w-8 grid place-items-center rounded-lg {{ $current_tab == self::BACK_OFFICE_TAB ? 'bg-white/15' : 'bg-violet-50 text-violet-600' }}"><i class="fa-solid fa-briefcase text-xs"></i></span>
                Back Office
            </button>
            <button type="button" wire:click="selectTab('{{ self::PENANDATANGAN_TAB }}')" onclick="switchTab('{{ self::PENANDATANGAN_TAB }}')"
                class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $current_tab == self::PENANDATANGAN_TAB ? 'bg-nest-700 text-white shadow-sm' : 'text-ink-700 hover:bg-ink-50' }}">
                <span class="h-8 w-8 grid place-items-center rounded-lg {{ $current_tab == self::PENANDATANGAN_TAB ? 'bg-white/15' : 'bg-amber-50 text-amber-600' }}"><i class="fa-solid fa-signature text-xs"></i></span>
                Penandatangan
            </button>
            <div class="my-2 border-t border-ink-100"></div>
            <button type="button" wire:click="selectTab('{{ self::NEW_PEGAWAI_TAB }}')" onclick="switchTab('{{ self::NEW_PEGAWAI_TAB }}')"
                class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-bold transition {{ $current_tab == self::NEW_PEGAWAI_TAB ? 'bg-ink-900 text-white shadow-sm' : 'text-nest-700 bg-nest-50 ring-1 ring-nest-200 hover:bg-nest-100' }}">
                <span class="h-8 w-8 grid place-items-center rounded-lg {{ $current_tab == self::NEW_PEGAWAI_TAB ? 'bg-white/15' : 'bg-nest-700 text-white' }}"><i class="fa-solid fa-user-plus text-xs"></i></span>
                Buat Pegawai Baru
            </button>
        </nav>
    </aside>

    {{-- content --}}
    <section class="relative min-h-[400px] rounded-2xl bg-white ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden">
        @if ($to_detail)
            <div class="p-5 sm:p-7">
                @include('livewire.admin.partials.pegawai-detail')
            </div>
        @endif

        {{-- FO / BO grid --}}
        <div class="{{ !$to_detail && in_array($current_tab, [self::FRONT_OFFICE_TAB, self::BACK_OFFICE_TAB]) ? '' : 'hidden' }}">
            <div class="flex items-center justify-between border-b border-ink-100 px-6 py-4">
                <div>
                    <h2 class="font-display font-extrabold text-[18px] text-ink-900">Pegawai {{ str_capitalize(str_replace('-', ' ', $current_tab), true) }}</h2>
                    <p class="text-xs text-ink-500">Klik nama untuk melihat & mengedit detail pegawai.</p>
                </div>
                <span class="rounded-full bg-ink-50 px-3 py-1 text-xs font-bold text-ink-600 ring-1 ring-ink-200">{{ count($users) }} orang</span>
            </div>
            <div class="grid gap-4 p-6 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($users as $user)
                    <div class="group overflow-hidden rounded-2xl ring-1 ring-ink-200 bg-white hover:ring-nest-300 hover:shadow-[0_12px_32px_rgba(15,23,42,0.10)] transition">
                        <div class="relative h-28 overflow-hidden bg-gradient-to-br from-nest-700 to-nest-800">
                            <img src="{{ asset('storage/users/' . $user->foto) }}" alt="{{ $user->name }}" class="absolute -bottom-6 left-1/2 h-20 w-20 -translate-x-1/2 rounded-2xl object-cover ring-4 ring-white shadow-lg">
                        </div>
                        <div class="px-4 pb-4 pt-8 text-center">
                            <h6 class="cursor-pointer text-sm font-extrabold text-ink-900 underline-offset-2 group-hover:text-nest-700 group-hover:underline transition" wire:click='userDetail({{ $user->id }})'>
                                {{ $user->pegawai->nama }}
                            </h6>
                            <p class="mt-0.5 text-xs text-ink-500"><i class="fa-solid fa-at"></i> {{ $user->username }}</p>
                            <button type="button" wire:click='userDetail({{ $user->id }})' class="mt-3 inline-flex items-center gap-1.5 rounded-full border border-ink-200 px-3.5 py-1.5 text-xs font-bold text-ink-700 hover:border-nest-300 hover:bg-nest-50 transition">
                                <i class="fa-solid fa-pen-to-square text-[10px]"></i> Kelola
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 xl:col-span-3 p-12 text-center">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-user-slash"></i></div>
                        <p class="mt-3 text-sm font-bold text-ink-900">Belum ada pegawai pada kategori ini</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- penandatangan list --}}
        <div class="{{ !$to_detail && $current_tab == self::PENANDATANGAN_TAB ? '' : 'hidden' }}">
            <div class="flex items-center justify-between border-b border-ink-100 px-6 py-4">
                <div>
                    <h2 class="font-display font-extrabold text-[18px] text-ink-900">Penandatangan</h2>
                    <p class="text-xs text-ink-500">Pejabat yang menandatangani dokumen perizinan.</p>
                </div>
                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700 ring-1 ring-amber-200">{{ count($penandatangan) }} pejabat</span>
            </div>
            <ul class="divide-y divide-ink-100 p-2">
                @forelse ($penandatangan as $atasan)
                    <li class="group flex items-start gap-4 rounded-xl px-4 py-4 hover:bg-ink-50 transition">
                        <img src="{{ asset('storage/web/' . ($atasan->gender == 'male' ? 'male.png' : 'female.png')) }}" alt="{{ $atasan->nama }}" class="h-14 w-14 shrink-0 rounded-xl object-cover ring-1 ring-ink-200 bg-nest-50">
                        <div class="min-w-0 flex-1">
                            <h6 class="cursor-pointer text-sm font-extrabold text-ink-900 underline-offset-2 hover:text-nest-700 hover:underline transition" wire:click='selectPenandatangan({{ $atasan->id }})'>
                                {{ $atasan->nama }}
                            </h6>
                            <p class="mt-0.5 text-xs font-semibold text-ink-600">{{ $atasan->jabatan }} <span class="mx-1 text-ink-300">•</span> {{ $atasan->pangkat }}</p>
                            <p class="text-xs text-ink-500">NIP. {{ $atasan->nip }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <button type="button" wire:click='selectPenandatangan({{ $atasan->id }})' class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-ink-600 ring-1 ring-ink-200 hover:bg-nest-50 hover:text-nest-700 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <button type="button" wire:click='deletePenandatangan({{ $atasan->id }})' wire:loading.class='opacity-40 pointer-events-none' wire:target='deletePenandatangan({{ $atasan->id }})'
                                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-red-500 ring-1 ring-red-200 hover:bg-red-50 transition">
                                <i class="fa-solid fa-trash text-xs" wire:loading.remove wire:target='deletePenandatangan({{ $atasan->id }})'></i>
                                <i class="fa-solid fa-spinner fa-spin text-xs hidden" wire:loading wire:target='deletePenandatangan({{ $atasan->id }})'></i>
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="p-12 text-center">
                        <div class="mx-auto h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-signature"></i></div>
                        <p class="mt-3 text-sm font-bold text-ink-900">Belum ada penandatangan</p>
                        <p class="text-sm text-ink-500">Tambahkan pejabat penandatangan dokumen.</p>
                    </li>
                @endforelse
            </ul>
        </div>

        @if (!$to_detail && $current_tab == self::NEW_PEGAWAI_TAB)
            <div class="px-5 py-8 sm:px-10">
                @livewire('admin.partials.pegawai-create')
            </div>
        @endif

        <div wire:loading wire:target='userDetail'>
            <div class="absolute inset-0 z-10 grid place-items-center bg-white/75 backdrop-blur-[2px]">
                <div class="flex flex-col items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin text-xl text-nest-700"></i>
                    <p class="text-xs font-bold text-ink-500">Memuat detail…</p>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- penandatangan edit popover panel --}}
<div class="modal-right {{ !$selected_penandatangan ? 'd-none' : '' }}">
    <div class="modal-right-content modal-right-content-minimalist p-0" style="right: {{ !$selected_penandatangan ? '-40%' : '0' }}">
        <div class="px-4 py-2 border-bottom d-flex justify-content-between align-items-center gap-2">
            <h6 class="text-semibold mb-0">Penandatangan</h6>
            <button class="ms-auto text-md btn btn-transparent p-0 card-icon sticky-top-0 shadow-none modal-right-show-btn" id="modalRightCloseBtn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="px-4 py-3 overflow-auto">
            <div wire:loading.remove wire:target='selectPenandatangan'>
                @if ($selected_penandatangan)
                    <div class="row">
                        <div class="col-5 mb-3">
                            <p class="text-sm text-medium mb-0">Avatar</p>
                        </div>
                        <div class="col-7 mb-3">
                            <input type="radio" class="btn-check" name="gender" wire:model='penandatangan_form.gender' value="male" id="male" autocomplete="off">
                            <label class="btn btn-outline-primary rounded-3 text-capitalize" for="male">
                                <img src="{{ asset('storage/web/male.png') }}" alt="male" class="img-rounded">
                            </label>

                            <input type="radio" class="btn-check" name="gender" wire:model='penandatangan_form.gender' value="female" id="female" autocomplete="off">
                            <label class="btn btn-outline-primary rounded-3 text-capitalize" for="female">
                                <img src="{{ asset('storage/web/female.png') }}" alt="female" class="img-rounded">
                            </label>
                        </div>
                        <div class="col-5 mb-3">
                            <p class="text-sm text-medium mb-0">Nama</p>
                        </div>
                        <div class="col-7 mb-3">
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.nama' placeholder="masukkan nama">
                            </div>
                        </div>
                        <div class="col-5 mb-3">
                            <p class="text-sm text-medium mb-0">Jabatan</p>
                        </div>
                        <div class="col-7 mb-3">
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.jabatan' placeholder="masukkan jabatan">
                            </div>
                        </div>
                        <div class="col-5 mb-3">
                            <p class="text-sm text-medium mb-0">Pangkat</p>
                        </div>
                        <div class="col-7 mb-3">
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.pangkat' placeholder="masukkan pangkat">
                            </div>
                        </div>
                        <div class="col-5 mb-3">
                            <p class="text-sm text-medium mb-0">NIP</p>
                        </div>
                        <div class="col-7 mb-3">
                            <div class="input-group input-group-sm border rounded-3">
                                <input type="text" class="form-control border-0 rounded-3" wire:model='penandatangan_form.nip' placeholder="masukkan nip">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div wire:loading.class='row' wire:target='selectPenandatangan'>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6 mb-3" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
                <div class="col-6" wire:loading wire:target='selectPenandatangan'>
                    <div class="loading-line"></div>
                </div>
            </div>
        </div>
        <div class="modal-right-footer px-4 py-2 border-top d-flex justify-content-between z-2 bg-white">
            <div class="ms-auto d-flex align-items-center gap-2">
                @if ($selected_penandatangan)
                    <button class="btn btn-sm bg-gradient-primary text-capitalize" type="button" wire:click='savePenandatangan'>
                        <span wire:loading.remove wire:target='savePenandatangan'>simpan</span>
                        <span class="spinner-border spinner-border-sm" wire:loading wire:target='savePenandatangan'></span>
                    </button>
                @endif
                <button class="btn btn-sm bg-gradient-light text-capitalize" type="button" id="modalRightCloseBtn" wire:click='cancelEditPenandatangan'>
                    batal
                </button>
            </div>
        </div>
    </div>
</div>
</div>
@push('scripts')
    <script>
        function switchTab(tab) {
            let url = new URL(window.location)
            url.searchParams.set('tab', tab)
            window.history.pushState({}, '', url)
        }
    </script>
@endpush
