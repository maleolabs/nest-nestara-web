<section aria-labelledby="jadwal-tim-title">
<div class="flex flex-wrap items-start justify-between gap-3">
    <div>
    <h2 id="jadwal-tim-title" class="font-bold text-ink-900">
        <i class="fa-regular fa-circle-right ml-1"></i>
        Penjadwalan tim teknis
    </h2>
    <p class="mb-4 text-sm text-ink-500">Tetapkan penandatangan untuk meneruskan permohonan ke tahap tinjauan lapangan.</p>
    </div>
    @if ($this->hasTinjauanLapangan() && $this->canEditTinjauanLapangan() && !$edit_tinjau_lapangan)
        <button class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition px-3 py-1.5 text-xs border border-ink-200 bg-white text-ink-800 hover:bg-ink-50" wire:click='editTinjauData' wire:loading.class='disabled' wire:target='editTinjauData'>
            <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-ink-300 border-t-ink-800" wire:loading wire:target='editTinjauData' aria-label="Memuat"></span>
            <span wire:loading.remove wire:target='editTinjauData'>
                <i class="fa-solid fa-square-pen ml-1 text-sm"></i>
                edit
            </span>
        </button>
    @endif
</div>
@if ($this->hasTinjauanLapangan() && !$edit_tinjau_lapangan)
            <p class="mb-1 text-ink-500 text-sm">Nomor surat</p>
    <p class="mb-3 font-semibold text-ink-900">{{ $saved_nosurat }}</p>
    <p class="mb-1 text-ink-500 text-sm">Penanda Tangan</p>
    <p class="mb-3 font-semibold text-ink-900">{{ $saved_penandatangan }}</p>
@else
    <form wire:submit.prevent="saveTinjauanLapangan">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="min-w-0">
                <label for="no_surat" class="mb-1 block text-sm font-semibold text-ink-700">Nomor Surat</label>
                <div class="mb-3">
                    <input type="text" class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 disabled @error('no_surat')border-red-500 @enderror" id="no_surat" wire:model.live="no_surat" disabled>
                </div>
                <label for="penandatangan" class="mb-1 block text-sm font-semibold text-ink-700">Penandatangan</label>
                <div class="@error('penandatangan')mb-1 @else @enderror">
                    <select class="w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-sm text-ink-900 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('penandatangan')border-red-500 @enderror" id="penandatangan" wire:model='penandatangan'>
                        <option value="">Pilih Penandatangan</option>
                        @foreach ($semua_penanda_tangan as $penanda_tangan)
                            <option value="{{ $penanda_tangan->id }}">{{ "{$penanda_tangan->nama} -> {$penanda_tangan->jabatan}" }}</option>
                        @endforeach
                    </select>
                </div>
                @error('penandatangan')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror
                <div class="mt-3">
                        <button type="submit" wire:loading.attr="disabled" wire:target="saveTinjauanLapangan" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition bg-nest-700 text-white disabled:cursor-not-allowed disabled:opacity-60">
                            <span wire:loading wire:target="saveTinjauanLapangan" class="mr-2 animate-spin">⟳</span>Simpan penjadwalan
                    </button>
                </div>
            </div>
        </div>
    </form>
@endif
</section>
