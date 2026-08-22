@extends('layouts.user-app')
@section('main')

{{-- HERO --}}
<section class="relative overflow-hidden bg-white">
  {{-- mesh gradient --}}
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -top-32 -right-32 h-[520px] w-[520px] rounded-full bg-nest-100 blur-[80px] opacity-70"></div>
    <div class="absolute -bottom-40 -left-40 h-[560px] w-[560px] rounded-full bg-amber-100 blur-[90px] opacity-60"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:40px_40px] opacity-[0.18]"></div>
  </div>

  <div class="relative mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
    <div class="grid lg:grid-cols-12 gap-10 items-center">
      <div class="lg:col-span-6" data-reveal>
        <div class="inline-flex items-center gap-2 rounded-full border border-nest-200 bg-nest-50 px-3 py-1 text-xs font-bold tracking-wide text-nest-800">
          <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
          LAYANAN PERIZINAN ONLINE {{ strtoupper(Version::productName()) }}
        </div>
        <h1 class="mt-4 font-display font-extrabold tracking-tight text-ink-900 text-[32px] sm:text-[42px] lg:text-[48px] leading-[0.95] text-balance">
          Perizinan Online
          <span class="bg-gradient-to-r from-nest-700 to-nest-500 bg-clip-text text-transparent">{{ Version::productName() }}</span>
          <span class="block text-ink-700 font-bold">cepat, transparan.</span>
        </h1>
        <p class="mt-4 text-[15px] sm:text-[16px] leading-relaxed text-ink-600 max-w-[60ch] text-balance">
          Ajukan, unggah dokumen, dan pantau status perizinan Anda secara real-time. Dirancang untuk pemohon, front-office, dan back-office — dengan jejak audit dan penandatanganan resmi.
        </p>

        <div class="mt-6 flex flex-wrap gap-3">
          @if (!auth()->check() || auth()->user()->hasRole('pemohon'))
            <a href="{{ route('create-izin') }}" class="inline-flex items-center gap-2 rounded-full bg-nest-700 px-6 py-3 text-sm font-extrabold text-white shadow-[0_12px_24px_rgba(15,118,110,0.28)] hover:bg-nest-800 hover:shadow-[0_16px_32px_rgba(15,118,110,0.34)] transition">
              Daftar Izin Baru <i class="fa-solid fa-arrow-right"></i>
            </a>
          @endif
          <a href="{{ route('jenis-perizinan') }}" class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-6 py-3 text-sm font-bold text-ink-800 hover:bg-ink-50 transition">
            <i class="fa-solid fa-layer-group text-ink-500"></i> Lihat Katalog
          </a>
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3 text-xs">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 ring-1 ring-ink-200 text-ink-700 font-semibold"><i class="fa-solid fa-shield-halved text-emerald-600"></i> Verifikasi berlapis</span>
          <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 ring-1 ring-ink-200 text-ink-700 font-semibold"><i class="fa-solid fa-clock text-amber-600"></i> Lacak status real-time</span>
          <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 ring-1 ring-ink-200 text-ink-700 font-semibold"><i class="fa-solid fa-file-shield text-nest-700"></i> Dokumen aman</span>
        </div>

        <div class="mt-8 grid grid-cols-3 gap-4 max-w-[520px]">
          <div class="rounded-2xl bg-ink-900 px-4 py-3 text-white">
            <p class="text-[15px] font-extrabold leading-tight">Online</p>
            <p class="text-[11px] font-semibold tracking-wide text-white/70 uppercase">Ajukan kapan saja</p>
          </div>
          <div class="rounded-2xl border border-ink-200 bg-white px-4 py-3">
            <p class="text-[15px] font-extrabold leading-tight text-ink-900">Terpantau</p>
            <p class="text-[11px] font-semibold tracking-wide text-ink-500 uppercase">Status real-time</p>
          </div>
          <div class="rounded-2xl border border-ink-200 bg-white px-4 py-3">
            <p class="text-[15px] font-extrabold leading-none text-ink-900">08–16</p>
            <p class="text-[11px] font-semibold tracking-wide text-ink-500 uppercase">WITA • Senin–Jumat</p>
          </div>
        </div>
      </div>

      <div class="lg:col-span-6" data-reveal>
        <div class="relative mx-auto w-full max-w-[560px]">
          {{-- dashboard preview mock --}}
          <div class="relative rounded-[28px] bg-white p-3 shadow-[0_20px_60px_rgba(15,23,42,0.12)] ring-1 ring-ink-200 overflow-hidden">
            <div class="flex items-center justify-between px-2 py-1">
              <div class="flex items-center gap-1.5"><span class="h-3 w-3 rounded-full bg-red-400"></span><span class="h-3 w-3 rounded-full bg-amber-400"></span><span class="h-3 w-3 rounded-full bg-emerald-400"></span></div>
              <span class="text-[11px] font-bold tracking-widest text-ink-400 uppercase">Preview Pemohon • Izin Saya</span>
              <span class="h-6 w-6 rounded-full bg-ink-50 grid place-items-center"><i class="fa-solid fa-ellipsis text-ink-400 text-xs"></i></span>
            </div>

            <div class="mt-3 rounded-2xl bg-ink-50 p-4 ring-1 ring-ink-100">
              <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-nest-700 grid place-items-center text-white"><i class="fa-solid fa-file-lines"></i></div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-bold text-ink-900 truncate">Izin Mendirikan Bangunan — Tahap Verifikasi</p>
                  <p class="text-xs text-ink-500">No. Reg: NEST-2026-00418 • Diajukan 21 Agu 2026</p>
                </div>
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-extrabold text-amber-800">Diproses</span>
              </div>
              <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                <div class="rounded-xl bg-white p-2.5 ring-1 ring-ink-200"><p class="text-[11px] font-bold tracking-wide text-ink-500 uppercase">Pendaftaran</p><p class="mt-1 text-sm font-extrabold text-emerald-600"><i class="fa-solid fa-check"></i> Selesai</p></div>
                <div class="rounded-xl bg-white p-2.5 ring-1 ring-nest-200"><p class="text-[11px] font-bold tracking-wide text-nest-700 uppercase">Verifikasi</p><p class="mt-1 text-sm font-extrabold text-nest-700">Berjalan</p></div>
                <div class="rounded-xl bg-white p-2.5 ring-1 ring-ink-200"><p class="text-[11px] font-bold tracking-wide text-ink-500 uppercase">Penetapan</p><p class="mt-1 text-sm font-bold text-ink-400">Menunggu</p></div>
              </div>
              <div class="mt-4 flex items-center gap-2">
                <div class="flex-1 h-2 rounded-full bg-white ring-1 ring-ink-200 overflow-hidden"><div class="h-full w-[62%] bg-gradient-to-r from-nest-600 to-nest-400"></div></div>
                <span class="text-xs font-bold text-ink-700">62%</span>
              </div>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-3">
              <div class="rounded-2xl border border-ink-200 bg-white p-3">
                <p class="text-xs font-bold tracking-wide text-ink-500 uppercase">Persyaratan</p>
                <p class="mt-1 text-sm font-bold text-ink-900">4/5 terunggah</p>
                <p class="text-xs text-ink-500">KTP, NIB, Siteplan</p>
              </div>
              <div class="rounded-2xl bg-nest-700 p-3 text-white">
                <p class="text-xs font-bold tracking-wide text-white/80 uppercase">Aksi cepat</p>
                <p class="mt-1 text-sm font-bold">Lengkapi Dokumen</p>
                <p class="text-xs text-white/70">2 file belum sesuai</p>
              </div>
            </div>
          </div>

          {{-- floating badges --}}
          <div class="hidden sm:flex absolute -left-6 top-10 items-center gap-2 rounded-full bg-white px-3 py-2 shadow-lg ring-1 ring-ink-200">
            <span class="h-8 w-8 rounded-full bg-emerald-50 grid place-items-center text-emerald-600"><i class="fa-solid fa-check"></i></span>
            <div><p class="text-xs font-extrabold text-ink-900 leading-none">Terverifikasi</p><p class="text-[11px] text-ink-500">Front Office</p></div>
          </div>
          <div class="hidden sm:flex absolute -right-4 bottom-10 items-center gap-2 rounded-full bg-ink-900 px-3 py-2 shadow-lg text-white">
            <span class="h-8 w-8 rounded-full bg-white/10 grid place-items-center"><i class="fa-solid fa-file-shield"></i></span>
            <div><p class="text-xs font-bold leading-none">QR & TTE</p><p class="text-[11px] text-white/70">Dokumen sah</p></div>
          </div>

          {{-- optional real illustration --}}
          <img src="{{ asset('storage/web/img-front2.png') }}" alt="" class="sr-only">
        </div>
      </div>
    </div>
  </div>
</section>

{{-- TRUST / FEATURES --}}
<section class="bg-ink-50 border-y border-ink-200">
  <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid md:grid-cols-3 gap-4">
      <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200" data-reveal>
        <div class="h-9 w-9 rounded-xl bg-nest-50 text-nest-700 grid place-items-center ring-1 ring-nest-200"><i class="fa-solid fa-bolt"></i></div>
        <h3 class="mt-3 font-bold text-ink-900">Alur yang jelas</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink-600">Tiga tahap baku: Pendaftaran → Verifikasi (Pendataan/Rekomendasi) → Penetapan. Setiap tahap tercatat.</p>
      </div>
      <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200" data-reveal>
        <div class="h-9 w-9 rounded-xl bg-amber-50 text-amber-700 grid place-items-center ring-1 ring-amber-200"><i class="fa-solid fa-shield-halved"></i></div>
        <h3 class="mt-3 font-bold text-ink-900">Keamanan dokumen</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink-600">Upload PDF/gambar dengan validasi. Preview, revisi, dan jejak perubahan tersimpan rapi.</p>
      </div>
      <div class="rounded-2xl bg-white p-5 ring-1 ring-ink-200" data-reveal>
        <div class="h-9 w-9 rounded-xl bg-sky-50 text-sky-700 grid place-items-center ring-1 ring-sky-200"><i class="fa-solid fa-chart-line"></i></div>
        <h3 class="mt-3 font-bold text-ink-900">Monitoring pegawai</h3>
        <p class="mt-1 text-sm leading-relaxed text-ink-600">Dashboard Front/Back Office dengan antrian, SLA, dan notifikasi — tanpa kehilangan berkas.</p>
      </div>
    </div>
  </div>
</section>

{{-- HOW IT WORKS --}}
<section class="bg-white">
  <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-2xl">
      <p class="text-xs font-extrabold tracking-widest text-nest-700 uppercase">Cara Kerja</p>
      <h2 class="mt-2 font-display font-extrabold text-[28px] leading-tight text-ink-900">Tiga langkah, satu kepastian.</h2>
      <p class="mt-2 text-sm leading-relaxed text-ink-600">Dirancang untuk memangkas waktu tunggu tanpa mengurangi ketelitian verifikasi.</p>
    </div>
    <div class="mt-8 grid lg:grid-cols-3 gap-4">
      <div class="relative rounded-2xl bg-ink-900 p-6 text-white overflow-hidden" data-reveal>
        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white text-ink-900 text-sm font-extrabold">1</span>
        <h3 class="mt-4 font-bold">Daftar & unggah</h3>
        <p class="mt-1 text-sm text-white/75 leading-relaxed">Pilih jenis izin, isi data pemohon & data izin, unggah persyaratan (PDF/gambar). Validasi instan.</p>
        <ul class="mt-4 space-y-1.5 text-xs text-white/80">
          <li class="flex gap-2"><i class="fa-solid fa-check mt-0.5"></i> Form wizard 3 langkah</li>
          <li class="flex gap-2"><i class="fa-solid fa-check mt-0.5"></i> Cegah file tidak sesuai</li>
        </ul>
      </div>
      <div class="rounded-2xl border border-ink-200 bg-ink-50 p-6" data-reveal>
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white ring-1 ring-ink-200 text-ink-900 text-sm font-extrabold">2</span>
        <h3 class="mt-4 font-bold text-ink-900">Verifikasi berlapis</h3>
        <p class="mt-1 text-sm text-ink-600 leading-relaxed">Front Office cek kelengkapan, Back Office lakukan pendataan hingga rekomendasi teknis.</p>
        <ul class="mt-4 space-y-1.5 text-xs text-ink-600">
          <li class="flex gap-2"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Notifikasi revisi jelas</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Jejak audit per proses</li>
        </ul>
      </div>
      <div class="rounded-2xl border border-ink-200 bg-white p-6" data-reveal>
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white ring-1 ring-ink-200 text-ink-900 text-sm font-extrabold">3</span>
        <h3 class="mt-4 font-bold text-ink-900">Penetapan & serah</h3>
        <p class="mt-1 text-sm text-ink-600 leading-relaxed">Surat keputusan terbit dengan QR/TTE. Unduh dan cetak kapan saja dari Izin Saya.</p>
        <ul class="mt-4 space-y-1.5 text-xs text-ink-600">
          <li class="flex gap-2"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> QR verifikasi keabsahan</li>
          <li class="flex gap-2"><i class="fa-solid fa-check text-nest-700 mt-0.5"></i> Riwayat & arsip lengkap</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- CTA / CONTACT --}}
<section class="bg-nest-950 text-white relative overflow-hidden">
  <div class="absolute inset-0 bg-[radial-gradient(600px_circle_at_20%_20%,rgba(45,212,191,0.18),transparent_60%),radial-gradient(800px_circle_at_90%_80%,rgba(245,158,11,0.14),transparent_60%)]"></div>
  <div class="relative mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid lg:grid-cols-12 gap-6 items-center">
      <div class="lg:col-span-8">
        <h3 class="font-display font-extrabold text-2xl">Siap mengajukan perizinan?</h3>
        <p class="mt-2 text-sm text-white/75 max-w-[60ch]">Buat akun, pilih jenis izin, dan ajukan dalam hitungan menit. Tim kami siap membantu jika Anda menemui kendala dokumen.</p>
        <div class="mt-4 flex flex-wrap gap-3">
          <a href="{{ route('create-izin') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-extrabold text-nest-900 hover:bg-ink-50 transition">Mulai Sekarang <i class="fa-solid fa-arrow-right"></i></a>
          @if (Version::organizationEmail())
          <a href="mailto:{{ Version::organizationEmail() }}" class="inline-flex items-center gap-2 rounded-full border border-white/20 px-5 py-2.5 text-sm font-bold text-white hover:bg-white/10 transition"><i class="fa-solid fa-envelope"></i> {{ Version::organizationEmail() }}</a>
          @endif
        </div>
      </div>
      <div class="lg:col-span-4 rounded-2xl bg-white/10 p-4 ring-1 ring-white/15 backdrop-blur">
        <p class="text-xs font-bold tracking-widest uppercase text-white/80">Jam Operasional</p>
        <p class="mt-1 text-sm font-bold">Senin – Jumat: 08.00 – 16.00 WITA</p>
        <p class="text-xs text-white/70">Di luar jam operasional, pengajuan tetap dapat dilakukan. Verifikasi diproses pada jam kerja.</p>
        <div class="mt-3 flex items-center gap-2 text-xs text-white/80"><i class="fa-solid fa-phone"></i> {{ Version::organizationPhone() }} • <span>{{ Version::organizationName() }}</span></div>
      </div>
    </div>
  </div>
</section>
@endsection
