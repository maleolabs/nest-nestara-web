@if (count($preview_izin_dokumen) > 0)
    <div class="my-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
        <strong class="font-bold">Penting:</strong>
        <p class="mt-1  leading-relaxed">
            Dokumen ini bersifat sangat sensitif dan rahasia. Pastikan untuk tidak menyimpan atau mengunduhnya di tempat yang tidak aman atau diakses oleh pihak yang tidak berwenang.
        </p>
    </div>
    @foreach ($preview_izin_dokumen as $dokumen)
        <div class="flex items-center justify-between gap-3 border-b border-ink-100 py-3 last:border-0">
            <p class=" min-w-0 text-sm font-semibold text-ink-800">{{ $dokumen->syarat_izin->nama }}</p>
            <div class="flex shrink-0 items-center gap-1">
            <a aria-label="Unduh {{ $dokumen->syarat_izin->nama }}" href="{{ \App\Support\SignedFileUrl::make($dokumen->nama_file) }}" class="grid h-9 w-9 place-items-center rounded-lg text-ink-500 hover:bg-ink-50 hover:text-nest-700">
                <i class="fa-solid fa-download text-sm"></i>
            </a>
            <a aria-label="Lihat {{ $dokumen->syarat_izin->nama }}" href="javascript:;" class="grid h-9 w-9 place-items-center rounded-lg text-ink-500 hover:bg-ink-50 hover:text-nest-700" wire:click="showImgModal('{{ $dokumen->syarat_izin->nama }}', '{{ base64_encode($dokumen->nama_file) }}', '{{ $dokumen->syarat_izin->tipe }}', {{ $dokumen->syarat_izin_id }})">
                <i class="fa-solid fa-eye text-sm"></i>
            </a>
            </div>
        </div>
    @endforeach
    @foreach ($preview_izin_syarat_izin as $syarat)
        <div class="flex items-center gap-3 border-b border-ink-100 py-3 text-sm text-ink-400 line-through last:border-0">
            <i class="fa-solid fa-download mr-1"></i>
            <i class="fa-solid fa-eye mr-1"></i>
            {{ $syarat->nama }}
            <span>{{ $syarat->nama }}</span>
        </div>
    @endforeach
@else
    <div class="rounded-xl border border-dashed border-ink-200 bg-ink-50 p-4" role="status">
        <p class=" text-sm text-ink-500">
            <i class="fa-solid fa-circle-info mr-1"></i>
            Tidak ada persyaratan untuk perizinan ini!
        </p>
    </div>
@endif
