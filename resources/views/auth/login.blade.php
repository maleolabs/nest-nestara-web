@extends('layouts.app')
@section('main')
<div class="min-h-screen grid lg:grid-cols-[1.05fr_1fr] bg-ink-50">
  {{-- brand panel --}}
  <div class="hidden lg:flex relative overflow-hidden bg-ink-900 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(700px_circle_at_30%_20%,rgba(45,212,191,0.22),transparent_60%),radial-gradient(800px_circle_at_80%_80%,rgba(245,158,11,0.18),transparent_60%)]"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:32px_32px] opacity-30"></div>
    <div class="relative flex flex-col justify-between p-10 w-full max-w-[560px] mx-auto">
      <div>
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3">
          <div class="h-10 w-10 rounded-xl bg-white grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-8 w-8 object-contain"></div>
          <div><p class="font-display font-extrabold tracking-tight">NESTARA</p><p class="text-xs tracking-widest text-white/70 uppercase">Bone Bolango • PMPTSP</p></div>
        </a>
        <h1 class="mt-10 font-display font-extrabold text-[34px] leading-[0.95] text-balance">Masuk dan kelola<br><span class="text-nest-300">perizinan Anda.</span></h1>
        <p class="mt-3 text-sm leading-relaxed text-white/70 max-w-[44ch]">Satu akun untuk mengajukan, memantau, dan mengunduh dokumen resmi. Aman, terverifikasi, dan teraudit.</p>
        <ul class="mt-6 space-y-3 text-sm">
          <li class="flex gap-3"><span class="h-7 w-7 rounded-full bg-white/10 grid place-items-center"><i class="fa-solid fa-shield-halved text-xs"></i></span><span><b class="text-white">Keamanan terjaga</b><br><span class="text-white/60">Password terenkripsi & sesi terlindungi</span></span></li>
          <li class="flex gap-3"><span class="h-7 w-7 rounded-full bg-white/10 grid place-items-center"><i class="fa-solid fa-clock text-xs"></i></span><span><b class="text-white">Pantau real-time</b><br><span class="text-white/60">Status izin update tanpa perlu datang</span></span></li>
        </ul>

        {{-- visual preview — fills empty area --}}
        <div class="mt-8 rounded-2xl overflow-hidden bg-white p-2 shadow-[0_16px_40px_rgba(0,0,0,0.25)] ring-1 ring-white/20">
          <div class="relative h-40 overflow-hidden rounded-xl">
            <img src="{{ asset('storage/web/gedung-mpp.png') }}" alt="Gedung MPP Bone Bolango" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-ink-900/70 via-ink-900/10 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-3">
              <p class="text-xs font-extrabold tracking-widest text-white/80 uppercase">Kantor Resmi</p>
              <p class="text-sm font-bold text-white leading-tight">Mal Pelayanan Publik Bone Bolango</p>
            </div>
            <span class="absolute top-2 right-2 rounded-full bg-emerald-500 px-2.5 py-1 text-[11px] font-extrabold text-white shadow">Buka • 08–16 WITA</span>
          </div>
          <div class="grid grid-cols-3 gap-2 p-2">
            <div class="rounded-xl bg-ink-50 px-2 py-2 text-center"><p class="text-[11px] font-bold tracking-wide text-ink-500 uppercase">Layanan</p><p class="text-sm font-extrabold text-nest-700">Perizinan</p></div>
            <div class="rounded-xl bg-ink-900 px-2 py-2 text-center text-white"><p class="text-[11px] font-bold tracking-wide text-white/60 uppercase">Jam</p><p class="text-sm font-extrabold">08–16</p></div>
            <div class="rounded-xl bg-amber-50 px-2 py-2 text-center ring-1 ring-amber-200"><p class="text-[11px] font-bold tracking-wide text-amber-700 uppercase">Hari</p><p class="text-sm font-extrabold text-amber-700">Sen–Jum</p></div>
          </div>
        </div>
      </div>
      <p class="text-xs text-white/50">© {{ date('Y') }} DPMPTSP Bone Bolango • Sistem Resmi Pemerintah Kabupaten</p>
    </div>
  </div>

  {{-- form panel --}}
  <div class="relative flex items-center justify-center p-4 sm:p-8 overflow-hidden">
    <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-nest-100 blur-[60px] opacity-60"></div>
    <div class="pointer-events-none absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-amber-100 blur-[60px] opacity-50"></div>
    <div class="w-full max-w-[440px]">
      <div class="lg:hidden flex items-center gap-3 mb-6">
        <a href="{{ route('welcome') }}" class="h-10 w-10 rounded-xl bg-white ring-1 ring-ink-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-8 w-8 object-contain"></a>
        <div><p class="font-display font-extrabold text-ink-900 leading-none">NESTARA</p><p class="text-xs tracking-widest text-ink-500 uppercase">PMPTSP Bone Bolango</p></div>
      </div>

      <div class="rounded-[24px] bg-white p-6 sm:p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)] ring-1 ring-ink-200">
        <h2 class="font-display font-extrabold text-[24px] text-ink-900">Selamat datang kembali</h2>
        <p class="mt-1 text-sm text-ink-600">Masuk untuk melanjutkan pengajuan perizinan.</p>

        @if ($errors->any())
          <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 ring-1 ring-red-200 text-sm text-red-700">
            <p class="font-bold flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> Periksa kembali</p>
            <ul class="mt-1 list-disc pl-5 space-y-0.5 text-xs">
              @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
          </div>
        @endif

        <form class="mt-6 space-y-4" action="{{ route('login') }}" method="POST">
          @csrf
          <div>
            <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Username</label>
            <div class="mt-1.5 relative">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-ink-400"><i class="fa-solid fa-user text-sm"></i></span>
              <input type="text" name="username" value="{{ old('username') }}" required autofocus placeholder="Masukkan username" class="w-full rounded-xl border border-ink-200 bg-white py-3 pl-10 pr-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('username') border-red-300 focus:border-red-400 focus:ring-red-100 @enderror">
            </div>
            @error('username')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
          </div>

          <div>
            <div class="flex items-center justify-between">
              <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Password</label>
              <a href="{{ route('password.request') }}" class="text-xs font-bold text-nest-700 hover:text-nest-800">Lupa password?</a>
            </div>
            <div class="mt-1.5 relative">
              <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-ink-400"><i class="fa-solid fa-lock text-sm"></i></span>
              <input id="password" type="password" name="password" required placeholder="••••••••" class="w-full rounded-xl border border-ink-200 bg-white py-3 pl-10 pr-11 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
              <button type="button" onclick="const i=document.getElementById('password'); i.type=i.type==='password'?'text':'password'; this.querySelector('i').classList.toggle('fa-eye'); this.querySelector('i').classList.toggle('fa-eye-slash')" class="absolute right-2 top-1/2 -translate-y-1/2 h-8 w-8 grid place-items-center rounded-full text-ink-500 hover:bg-ink-50"><i class="fa-solid fa-eye text-sm"></i></button>
            </div>
          </div>

          <label class="flex items-center gap-2 text-sm font-medium text-ink-700">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-ink-300 text-nest-700 focus:ring-nest-600"> Ingat saya
          </label>

          <button type="submit" class="w-full rounded-full bg-nest-700 py-3 text-sm font-extrabold text-white shadow-[0_10px_24px_rgba(15,118,110,0.28)] hover:bg-nest-800 transition">Masuk <i class="fa-solid fa-arrow-right ml-1"></i></button>

          <p class="text-center text-sm text-ink-600">Belum punya akun? <a href="{{ route('register') }}" class="font-extrabold text-nest-700 hover:text-nest-800">Daftar sekarang</a></p>
        </form>
      </div>

      <p class="mt-4 text-center text-xs text-ink-500">Dengan masuk, Anda menyetujui kebijakan privasi & ketentuan layanan.</p>
    </div>
  </div>
</div>
@endsection
