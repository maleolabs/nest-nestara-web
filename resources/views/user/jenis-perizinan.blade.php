@extends('layouts.user-app')
@section('main')
<div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-8">
  {{-- header --}}
  <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
      <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Katalog Resmi</p>
      <h1 class="mt-1 font-display font-extrabold text-[28px] leading-tight text-ink-900">Jenis Perizinan</h1>
      <p class="mt-1 text-sm text-ink-600 max-w-[60ch]">Pilih jenis izin sesuai kebutuhan. Setiap izin memiliki persyaratan dan alur verifikasi yang transparan.</p>
    </div>
    <form method="GET" class="flex w-full lg:w-auto items-center gap-2">
      <div class="relative flex-1 lg:w-[360px]">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-ink-400 text-sm"></i>
        <input name="q" value="{{ $q ?? '' }}" placeholder="Cari izin, mis. IMB, OSS, Kesehatan..." class="w-full rounded-full border border-ink-200 bg-white py-2.5 pl-9 pr-4 text-sm font-medium text-ink-800 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100" />
      </div>
      <button type="submit" class="rounded-full bg-ink-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-ink-800 transition">Cari</button>
    </form>
  </div>

  {{-- grid --}}
  <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    @forelse ($all_perizinan as $perizinan)
      <a href="{{ route('create-izin') }}?jenis={{ $perizinan->id }}" class="group relative flex flex-col rounded-2xl bg-white p-5 ring-1 ring-ink-200 hover:ring-nest-200 hover:shadow-[0_12px_32px_rgba(15,23,42,0.08)] transition">
        <div class="flex items-start justify-between gap-3">
          <div class="h-10 w-10 rounded-xl bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200 group-hover:bg-nest-700 group-hover:text-white transition">
            <i class="fa-solid fa-file-shield"></i>
          </div>
          <span class="rounded-full bg-ink-50 px-2.5 py-1 text-[11px] font-bold tracking-wide text-ink-600 ring-1 ring-ink-200">Syarat {{ $perizinan->persyaratan_count ?? '—' }}</span>
        </div>
        <h3 class="mt-3 font-bold text-ink-900 leading-snug line-clamp-2">{{ $perizinan->nama }}</h3>
        <p class="mt-1 text-xs leading-relaxed text-ink-500 line-clamp-2">{{ Str::limit($perizinan->deskripsi ?? 'Perizinan resmi DPMPTSP Bone Bolango dengan alur verifikasi berlapis dan penetapan terverifikasi.', 110) }}</p>
        <div class="mt-4 flex items-center gap-2 text-xs font-bold text-nest-700">
          Ajukan sekarang <i class="fa-solid fa-arrow-right text-[11px] group-hover:translate-x-0.5 transition"></i>
        </div>
      </a>
    @empty
      <div class="sm:col-span-2 lg:col-span-3 rounded-2xl border border-dashed border-ink-200 bg-white p-10 text-center">
        <div class="mx-auto h-12 w-12 rounded-2xl bg-ink-50 grid place-items-center text-ink-400"><i class="fa-solid fa-layer-group"></i></div>
        <p class="mt-3 text-sm font-bold text-ink-900">Belum ada data perizinan</p>
        <p class="text-sm text-ink-500">Silakan hubungi admin untuk sinkronisasi katalog.</p>
      </div>
    @endforelse
  </div>

  <div class="mt-6 flex justify-center">
    <x-pagination-nestara :paginator="$all_perizinan" :livewire="false" />
  </div>

  <div class="mt-8 rounded-2xl bg-ink-900 p-6 text-white flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
      <p class="font-bold">Tidak menemukan izin yang dicari?</p>
      <p class="text-sm text-white/70">Hubungi layanan bantuan — kami bantu identifikasi jenis izin yang tepat.</p>
    </div>
    <a href="mailto:support@perizinanpmptsp.go.id" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-extrabold text-ink-900">Hubungi Support</a>
  </div>
</div>
@endsection
