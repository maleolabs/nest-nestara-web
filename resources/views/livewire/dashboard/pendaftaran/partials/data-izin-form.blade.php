@php
    $inputBase = 'mt-1.5 w-full rounded-xl border bg-white px-4 py-2.5 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-4 focus:ring-nest-100 transition';
    $inputOk = 'border-ink-200 focus:border-nest-600';
@endphp

<form wire:submit.prevent='save'>
    @csrf
    <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
        {{-- kolom form --}}
        <div class="space-y-5">
            {{-- cari pemohon --}}
            <section class="rounded-xl border border-ink-200 p-4">
                <h6 class="text-xs font-extrabold tracking-widest text-ink-500 uppercase">Pemohon Izin</h6>
                <div class="relative mt-3">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-sm"></i>
                    <input type="text" id="cari_pemohon" wire:model.live.debounce.300ms='cari_pemohon' placeholder="Cari nama atau ID pemohon…"
                        class="{{ $inputBase }} {{ $inputOk }} pl-10">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 hidden" wire:loading wire:target='cari_pemohon'>
                        <i class="fa-solid fa-spinner fa-spin text-nest-700"></i>
                    </span>
                </div>
                <p class="mt-2 text-[11px] text-ink-500">Ketik minimal 1 karakter — hasil muncul pada pilihan di bawah.</p>
            </section>

            {{-- data permohonan --}}
            <section class="rounded-xl border border-ink-200 p-4">
                <h6 class="text-xs font-extrabold tracking-widest text-ink-500 uppercase">Data Permohonan</h6>
                <div class="mt-3">
                    <label for="jenisPerizinanSelect" class="text-xs font-bold tracking-wide text-ink-700 uppercase">Jenis Izin</label>
                    <select id="jenisPerizinanSelect" wire:model='data_izin_form.jenis_izin'
                        class="{{ $inputBase }} {{ $errors->has('data_izin_form.jenis_izin') ? 'border-red-300 focus:border-red-400 focus:ring-red-100' : $inputOk }}">
                        @foreach ($allPerizinan as $perizinan)
                            <option value="{{ $perizinan->nama }}">{{ $perizinan->nama }}</option>
                        @endforeach
                    </select>
                    @error('data_izin_form.jenis_izin')
                        <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </section>
        </div>

        {{-- sidebar info & aksi --}}
        <aside class="h-fit lg:sticky lg:top-[88px] space-y-4">
            <div class="rounded-2xl bg-ink-50 p-4 ring-1 ring-ink-200">
                <p class="text-sm font-extrabold text-ink-900"><i class="fa-solid fa-circle-info mr-1.5 text-nest-700"></i>Sebelum menyimpan</p>
                <ul class="mt-2 space-y-1.5 text-xs leading-relaxed text-ink-600">
                    <li class="flex gap-1.5"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Pastikan pemohon sudah terdaftar — jika belum, buat Data Pemohon dulu.</li>
                    <li class="flex gap-1.5"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Pilih jenis izin sesuai kebutuhan pemohon.</li>
                    <li class="flex gap-1.5"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Setelah tersimpan, permohonan masuk ke alur verifikasi FO.</li>
                </ul>
            </div>

            <div class="space-y-2">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-nest-700 px-5 py-3 text-sm font-extrabold text-white shadow-[0_10px_24px_rgba(15,118,110,0.28)] hover:bg-nest-800 transition disabled:opacity-50"
                    wire:loading.attr="disabled" wire:target='save'>
                    <span wire:loading.remove wire:target='save'><i class="fa-solid fa-floppy-disk"></i> Simpan Permohonan</span>
                    <span wire:loading wire:target='save'><i class="fa-solid fa-spinner fa-spin"></i> Menyimpan…</span>
                </button>
                <button type="button" wire:click='resetData'
                    class="w-full inline-flex items-center justify-center gap-2 rounded-full border border-ink-200 bg-white px-5 py-3 text-sm font-bold text-ink-700 hover:bg-ink-50 transition">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </aside>
    </div>
</form>
