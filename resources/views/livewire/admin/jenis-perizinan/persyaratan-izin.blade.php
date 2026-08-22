<div>
    <md-linear-progress wire:loading wire:target='saveTipeSyarat, savePemasukanBerkas, addNewSyarat,saveNewSyarat' class="fixed top-0 left-0 w-full" indeterminate style="--md-linear-progress-active-indicator-color: #0f766e; z-index: 9999"></md-linear-progress>

    <div class="flex items-center justify-between gap-3">
        <div>
            <h2 class="font-display font-extrabold text-[18px] text-ink-900 leading-tight">Persyaratan</h2>
            <p class="text-xs text-ink-500">Dokumen yang wajib dilengkapi pemohon untuk jenis izin ini.</p>
        </div>
        <span class="rounded-full bg-nest-50 px-3 py-1.5 text-xs font-bold text-nest-700 ring-1 ring-nest-200">{{ count($preview_jenis->syarat_izin) }} syarat</span>
    </div>

    <div class="mt-5 overflow-hidden rounded-xl ring-1 ring-ink-200">
        @if (count($preview_jenis->syarat_izin) > 0)
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-ink-200 bg-ink-50/60">
                        <th class="px-4 py-3 text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Nama Syarat</th>
                        <th class="px-4 py-3 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Tipe</th>
                        <th class="px-4 py-3 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Pemasukan Berkas</th>
                        <th class="px-4 py-3 text-center text-[11px] font-extrabold uppercase tracking-widest text-ink-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($preview_jenis->syarat_izin as $syarat)
                        <tr class="hover:bg-nest-50/40 transition">
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-ink-900">
                                    <span class="text-ink-400 mr-1">{{ $loop->iteration }}.</span>
                                    {{ str_capitalize($syarat->nama, true) }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <select wire:model.live='tipe.{{ $syarat->id }}' id="tipe-{{ $syarat->id }}"
                                    class="rounded-lg border border-ink-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-ink-800 focus:border-nest-600 focus:outline-none focus:ring-2 focus:ring-nest-100">
                                    <option value="img">Gambar</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <select wire:model.live='pemasukan_berkas.{{ $syarat->id }}' id="pemasukan_berkas-{{ $syarat->id }}"
                                    class="rounded-lg border px-2.5 py-1.5 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-nest-100 {{ $pemasukan_berkas[$syarat->id] == 'online' ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-ink-50 border-ink-200 text-ink-600' }}">
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                </select>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button type="button" wire:click='deleteSyarat({{ $syarat->id }})'
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-red-500 ring-1 ring-red-200 hover:bg-red-50 transition"
                                    wire:loading.attr="disabled" wire:target='deleteSyarat({{ $syarat->id }})'>
                                    <i class="fa-solid fa-trash text-xs" wire:loading.remove wire:target='deleteSyarat({{ $syarat->id }})'></i>
                                    <i class="fa-solid fa-spinner fa-spin text-xs hidden" wire:loading wire:target='deleteSyarat({{ $syarat->id }})'></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-10 text-center">
                <div class="mx-auto h-12 w-12 rounded-2xl bg-amber-50 grid place-items-center text-amber-500 ring-1 ring-amber-200"><i class="fa-solid fa-clipboard-list"></i></div>
                <p class="mt-3 text-sm font-bold text-ink-900">Perizinan ini belum memiliki persyaratan</p>
                <p class="text-sm text-ink-500">Tambahkan melalui pencarian di bawah.</p>
            </div>
        @endif
    </div>

    {{-- tambah syarat --}}
    <div class="mt-6 max-w-md">
        <p class="text-sm font-extrabold text-ink-900">Tambah Persyaratan Baru</p>

        @if (count($new_syarat) > 0)
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($new_syarat as $id_new => $new)
                    <span class="inline-flex items-center gap-2 rounded-full bg-sky-50 pl-3 pr-2 py-1.5 text-xs font-bold text-sky-700 ring-1 ring-sky-200">
                        {{ $new }}
                        <button type="button" wire:click="unsetNewSyarat('{{ $id_new }}')" class="h-4 w-4 grid place-items-center rounded-full bg-sky-200/60 hover:bg-sky-300 transition"><i class="fa-solid fa-x text-[9px]"></i></button>
                    </span>
                @endforeach
            </div>
        @endif

        <div class="relative mt-3">
            <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-sm"></i>
            <input type="search" wire:model.lazy='search_new_syarat' placeholder="Cari atau buat syarat baru…"
                class="w-full rounded-xl border border-ink-200 bg-white py-2.5 pl-10 pr-9 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
            <span class="absolute right-3 top-1/2 -translate-y-1/2" wire:loading wire:target='search_new_syarat'>
                <i class="fa-solid fa-spinner fa-spin text-nest-700 text-sm"></i>
            </span>

            {{-- hasil pencarian --}}
            <div {{ count($search_syarat) <= 0 ? 'hidden' : '' }}
                class="absolute left-0 right-0 top-[calc(100%+6px)] max-h-60 overflow-y-auto rounded-xl bg-white shadow-[0_16px_40px_rgba(15,23,42,0.14)] ring-1 ring-ink-200 z-20 animate-fadeIn">
                @foreach ($search_syarat as $id_search => $search_result)
                    <button type="button" wire:click="addNewSyarat('{{ $id_search }}', '{{ $search_result }}')"
                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-left text-sm font-semibold text-ink-700 hover:bg-nest-50 hover:text-nest-800 transition border-b border-ink-100 last:border-b-0">
                        <i class="fa-solid fa-plus text-[10px] text-nest-700"></i> {{ $search_result }}
                    </button>
                @endforeach
            </div>
        </div>

        <button type="button" wire:click='saveNewSyarat' {{ count($new_syarat) == 0 ? 'disabled' : '' }}
            class="mt-4 inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-bold transition disabled:opacity-40 disabled:cursor-not-allowed {{ count($new_syarat) == 0 ? 'bg-ink-100 text-ink-400' : 'bg-nest-700 text-white shadow-[0_8px_20px_rgba(15,118,110,0.25)] hover:bg-nest-800' }}">
            <i class="fa-solid fa-floppy-disk"></i> Simpan Persyaratan
        </button>
    </div>
</div>
</div>
