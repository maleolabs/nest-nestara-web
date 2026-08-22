@extends('layouts.app')
@section('main')
<div class="min-h-screen grid lg:grid-cols-[1.05fr_1fr] bg-ink-50">
  <div class="hidden lg:flex relative overflow-hidden bg-ink-900 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(700px_circle_at_30%_20%,rgba(45,212,191,0.22),transparent_60%),radial-gradient(800px_circle_at_80%_80%,rgba(245,158,11,0.18),transparent_60%)]"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:32px_32px] opacity-30"></div>
    <div class="relative flex flex-col justify-between p-10 w-full max-w-[560px] mx-auto">
      <div>
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-3">
          <div class="h-10 w-10 rounded-xl bg-white grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-8 w-8 object-contain"></div>
          <div><p class="font-display font-extrabold tracking-tight">{{ strtoupper(Version::productName()) }}</p><p class="text-xs tracking-widest text-white/70 uppercase">{{ Version::organizationName() }}</p></div>
        </a>
        <h1 class="mt-10 font-display font-extrabold text-[34px] leading-[0.95] text-balance">Buat akun<br><span class="text-nest-300">dalam 1 menit.</span></h1>
        <p class="mt-3 text-sm leading-relaxed text-white/70 max-w-[44ch]">Daftar sekali, ajukan berbagai jenis perizinan kapan saja. Data Anda terenkripsi dan hanya digunakan untuk verifikasi resmi.</p>
        <div class="mt-6 rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
          <p class="text-xs font-bold tracking-widest uppercase text-white/70">Alur setelah daftar</p>
          <ol class="mt-2 space-y-1.5 text-sm text-white/90">
            <li class="flex gap-2"><span class="h-6 w-6 rounded-full bg-white text-ink-900 grid place-items-center text-xs font-bold">1</span> Verifikasi email</li>
            <li class="flex gap-2"><span class="h-6 w-6 rounded-full bg-white text-ink-900 grid place-items-center text-xs font-bold">2</span> Lengkapi profil & ajukan izin</li>
            <li class="flex gap-2"><span class="h-6 w-6 rounded-full bg-white text-ink-900 grid place-items-center text-xs font-bold">3</span> Pantau status di Izin Saya</li>
          </ol>
        </div>

        <div class="mt-6 rounded-2xl overflow-hidden bg-white p-2 shadow-[0_16px_40px_rgba(0,0,0,0.25)] ring-1 ring-white/20">
          <div class="relative h-36 overflow-hidden rounded-xl">
            <img src="{{ asset('storage/web/building.png') }}" alt="Kantor layanan perizinan" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-ink-900/60 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-3">
              <p class="text-xs font-bold text-white">{{ Version::organizationName() }}</p>
              <p class="text-[11px] text-white/70">Loket layanan perizinan terpadu</p>
            </div>
          </div>
          <div class="p-2 text-center">
            <p class="text-xs font-bold text-ink-900">Satu akun untuk semua jenis izin</p>
            <p class="text-[11px] text-ink-500">Pantau status pengajuan kapan saja melalui menu Izin Saya</p>
          </div>
        </div>
      </div>
      <p class="text-xs text-white/50">© {{ date('Y') }} {{ Version::organizationName() }} • Resmi & Teraudit</p>
    </div>
  </div>

  <div class="relative flex items-center justify-center p-4 sm:p-8 py-8 overflow-hidden">
    <div class="pointer-events-none absolute -top-20 -right-20 h-64 w-64 rounded-full bg-nest-100 blur-[60px] opacity-50"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-amber-100 blur-[60px] opacity-40"></div>
    <div class="w-full max-w-[480px]">
      <div class="lg:hidden flex items-center gap-3 mb-6">
        <a href="{{ route('welcome') }}" class="h-10 w-10 rounded-xl bg-white ring-1 ring-ink-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-8 w-8 object-contain"></a>
        <div><p class="font-display font-extrabold text-ink-900 leading-none">{{ strtoupper(Version::productName()) }}</p><p class="text-xs tracking-widest text-ink-500 uppercase">{{ Version::organizationName() }}</p></div>
      </div>

      <div class="rounded-[24px] bg-white p-6 sm:p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)] ring-1 ring-ink-200">
        <h2 class="font-display font-extrabold text-[24px] text-ink-900">Daftar akun pemohon</h2>
        <p class="mt-1 text-sm text-ink-600">Gunakan data yang valid — akan digunakan untuk penerbitan dokumen resmi.</p>

        <form class="mt-6 space-y-4" action="{{ route('register') }}" method="POST">
          @csrf
          <div>
            <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama sesuai KTP" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white py-3 px-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('name') border-red-300 @enderror">
            @error('name')<p class="mt-1 text-xs font-semibold text-red-600"><i class="fa-solid fa-circle-info mr-1"></i>{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" required placeholder="username tanpa spasi" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white py-3 px-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('username') border-red-300 @enderror">
            @error('username')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white py-3 px-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('email') border-red-300 @enderror">
            @error('email')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
          </div>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Password</label>
              <input type="password" name="password" required placeholder="Min. 8 karakter" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white py-3 px-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100 @error('password') border-red-300 @enderror">
              @error('password')<p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
              <label class="text-xs font-bold tracking-wide text-ink-700 uppercase">Konfirmasi Password</label>
              <input type="password" name="password_confirmation" required placeholder="Ulangi password" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white py-3 px-4 text-sm font-medium text-ink-900 placeholder:text-ink-400 focus:border-nest-600 focus:outline-none focus:ring-4 focus:ring-nest-100">
            </div>
          </div>

          <p class="text-xs leading-relaxed text-ink-500">Dengan mendaftar, Anda menyetujui <span class="font-bold text-ink-700">Syarat Layanan</span> dan <span class="font-bold text-ink-700">Kebijakan Privasi</span>.</p>

          <button type="submit" class="w-full rounded-full bg-nest-700 py-3 text-sm font-extrabold text-white shadow-[0_10px_24px_rgba(15,118,110,0.28)] hover:bg-nest-800 transition">Buat Akun <i class="fa-solid fa-arrow-right ml-1"></i></button>

          <p class="text-center text-sm text-ink-600">Sudah punya akun? <a href="{{ route('login') }}" class="font-extrabold text-nest-700 hover:text-nest-800">Masuk</a></p>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
