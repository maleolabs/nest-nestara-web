<div class="mx-auto max-w-[960px] px-4 sm:px-6 lg:px-8 py-6">
    @if ($to_confirm_delete)
        <div class="mx-auto max-w-[520px]">
            <div class="rounded-2xl bg-white p-6 ring-1 ring-red-200 shadow-[0_8px_24px_rgba(15,23,42,0.06)]">
                <div class="flex items-start gap-3">
                    <span class="h-10 w-10 shrink-0 rounded-xl bg-red-50 text-red-600 grid place-items-center ring-1 ring-red-200"><i class="fa-solid fa-triangle-exclamation"></i></span>
                    <div>
                        <h5 class="font-display font-extrabold text-[18px] text-ink-900 leading-tight">Konfirmasi Penghapusan Permohonan</h5>
                        <p class="mt-1 text-sm leading-relaxed text-ink-600">Tindakan ini bersifat <b class="text-red-600">permanen</b> dan tidak dapat dibatalkan. Seluruh data terkait permohonan izin ini akan dihapus.</p>
                    </div>
                </div>
                <div class="mt-4 rounded-xl border-2 border-dashed border-ink-200 p-4">
                    <label class="flex items-center gap-2.5 text-sm font-semibold text-ink-700 cursor-pointer">
                        <input type="checkbox" wire:model.live='confirm_delete' class="h-4 w-4 rounded-lg border-ink-300 text-red-600 focus:ring-red-200">
                        Saya memahami konsekuensi penghapusan ini
                    </label>
                    <button type="button" wire:click='confirmDelete' {{ !$confirm_delete ? 'disabled' : '' }}
                        class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-full bg-red-600 px-5 py-3 text-sm font-extrabold text-white hover:bg-red-700 transition disabled:opacity-40 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled" wire:target='confirmDelete'>
                        <span wire:loading.remove wire:target='confirmDelete'><i class="fa-solid fa-trash"></i> Hapus Permanen</span>
                        <span wire:loading wire:target='confirmDelete'><i class="fa-solid fa-spinner fa-spin"></i> Menghapus…</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- header --}}
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Ruang Pemohon</p>
                <h1 class="mt-1 font-display font-extrabold text-[26px] leading-tight text-ink-900">Izin Saya</h1>
                <p class="mt-1 text-sm text-ink-600">{{ count($semua_izin) }} permohonan terdaftar atas nama Anda.</p>
            </div>
            <a href="{{ route('create-izin') }}" class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-5 py-2.5 text-sm font-bold text-white shadow-[0_8px_20px_rgba(15,118,110,0.25)] hover:bg-nest-800 transition">
                <i class="fa-solid fa-plus text-xs"></i> Buat Izin Baru
            </a>
        </div>

        {{-- list izin --}}
        <div class="space-y-3">
            @forelse ($semua_izin->reverse() as $izin)
                @php
                    $is_izin_new = $izin->created_at >= \Carbon\Carbon::now()->subMinutes(30);
                    $status = $izin->status_izin->last();
                    $sc = getStatusIzinClasses($status);
                @endphp
                <div class="rounded-2xl bg-white p-4 ring-1 ring-ink-200 shadow-[0_4px_20px_rgba(15,23,42,0.05)] {{ $is_izin_new ? 'ring-2 ring-nest-500' : '' }}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                            <button type="button" id="modalRightShowBtn-{{ $izin->id }}" wire:click='preview({{ $izin->id }})' class="min-w-0 flex-1 text-left group">
                            <div class="flex items-center gap-2">
                                <h3 class="font-bold text-[15px] text-ink-900 group-hover:text-nest-700 transition">{{ $izin->jenis_perizinan->nama }}</h3>
                                @if ($is_izin_new)
                                    <span class="rounded-full bg-nest-50 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide text-nest-700 ring-1 ring-nest-200">Baru</span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-ink-500">No. Pendaftran {{ $izin->no_pendaftaran }} • Diajukan {{ $izin->created_at->diffForHumans() }}</p>
                            <span class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-bold ring-1 ring-ink-200 {{ $sc['textClass'] }}">
                                <i class="{{ $sc['bgClass'] }} h-1.5 w-1.5 rounded-full"></i> {{ $status->nama }}
                            </span>
                        </button>

                        @if ($this->isIzinCancelable($status->nama))
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                                <button type="button" @click="open = !open" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-ink-500 ring-1 ring-ink-200 hover:bg-ink-50 transition" id="cancelIzinAction-{{ $izin->id }}" :aria-expanded="open.toString()" aria-haspopup="menu">
                                    <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                                </button>
                                <ul x-cloak x-show="open" x-transition class="absolute right-0 z-10 mt-2 w-56 rounded-xl border border-ink-200 bg-white p-1 shadow-lg" aria-labelledby="cancelIzinAction-{{ $izin->id }}" role="menu">
                                    <li>
                                        <button type="button" class="w-full rounded-lg px-3 py-2 text-left text-sm font-semibold text-red-600 hover:bg-red-50" wire:click='cancel({{ $izin->id }})' role="menuitem">
                                            <i class="fa-solid fa-circle-minus mr-2"></i> Batalkan Permohonan
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-ink-200 bg-white p-12 text-center">
                    <div class="mx-auto h-14 w-14 rounded-2xl bg-ink-50 grid place-items-center text-ink-400 ring-1 ring-ink-200"><i class="fa-solid fa-file-circle-plus text-xl"></i></div>
                    <p class="mt-3 text-sm font-bold text-ink-900">Belum ada permohonan izin</p>
                    <p class="mt-1 text-sm text-ink-500">Ajukan permohonan pertama Anda melalui tombol di atas.</p>
                </div>
            @endforelse

            @if (count($semua_izin) > 0)
                <div class="pt-2">
                    {{ $semua_izin->links('components.pagination-nestara', ['livewire' => true]) }}
                </div>
            @endif
        </div>

        @include('livewire.helper.modal-right')
    @endif
</div>
