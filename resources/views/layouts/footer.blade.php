<footer class="mt-12 border-t border-ink-200 bg-white">
  <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid gap-8 md:grid-cols-12">
      <div class="md:col-span-5">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-xl bg-nest-50 ring-1 ring-nest-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-7 w-7 object-contain"></div>
          <div>
            <p class="font-display font-extrabold tracking-tight text-ink-900">NESTARA</p>
            <p class="text-xs font-medium tracking-widest text-ink-500 uppercase">DPMPTSP Kabupaten Bone Bolango</p>
          </div>
        </div>
        <p class="mt-4 text-sm leading-relaxed text-ink-600 max-w-[46ch]">
          Sistem Informasi Perizinan Online yang transparan dan terintegrasi. Ajukan, pantau, dan kelola perizinan Anda dengan mudah — dirancang untuk kecepatan, akuntabilitas, dan kepastian hukum.
        </p>
        <div class="mt-4 flex items-center gap-2 text-xs font-semibold text-ink-500">
          <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 ring-1 ring-emerald-200 text-emerald-700"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Layanan aktif</span>
          <span>Senin–Jumat 08.00–16.00 WITA</span>
        </div>
      </div>
      <div class="md:col-span-3">
        <p class="text-xs font-bold tracking-widest text-ink-900 uppercase">Layanan</p>
        <ul class="mt-3 space-y-2 text-sm text-ink-600">
          <li><a href="{{ route('jenis-perizinan') }}" class="hover:text-nest-700">Katalog Perizinan</a></li>
          <li><a href="{{ route('create-izin') }}" class="hover:text-nest-700">Buat Perizinan Baru</a></li>
          <li><a href="{{ route('user.izin') }}" class="hover:text-nest-700">Lacak Izin Saya</a></li>
          <li><a href="#" class="hover:text-nest-700">Panduan & FAQ</a></li>
        </ul>
      </div>
      <div class="md:col-span-4">
        <p class="text-xs font-bold tracking-widest text-ink-900 uppercase">Kontak Resmi</p>
        <div class="mt-3 space-y-2 text-sm">
          <p class="flex items-center gap-2 text-ink-700"><i class="fa-solid fa-envelope text-ink-400"></i> support@perizinanpmptsp.go.id</p>
          <p class="flex items-center gap-2 text-ink-700"><i class="fa-solid fa-phone text-ink-400"></i> (021) 123-4567</p>
          <p class="flex items-start gap-2 text-ink-600"><i class="fa-solid fa-location-dot text-ink-400 mt-1"></i> Kompleks Perkantoran Bone Bolango, Gorontalo</p>
        </div>
      </div>
    </div>
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-3 border-t border-ink-100 pt-6 text-xs text-ink-500">
      <p>© {{ date('Y') }} DPMPTSP Bone Bolango — Pemerintah Kabupaten Bone Bolango. All rights reserved.</p>
      <p class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-nest-500"></span> Sistem Perizinan Online Terpadu</p>
    </div>
  </div>
</footer>

@livewireScripts
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/livewire-event.js') }}"></script>
@stack('scripts')
