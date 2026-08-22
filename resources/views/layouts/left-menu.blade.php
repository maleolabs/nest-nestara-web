{{-- Left menu — popover (not slider) for all screens --}}
<div id="sideMenuLeftContainer" class="hidden fixed inset-0 z-40">
  <div id="sideMenuLeftBackdrop" class="absolute inset-0 bg-ink-900/10 backdrop-blur-[1px]"></div>
  <div class="absolute left-3 sm:left-4 top-[72px] w-[360px] max-w-[92vw] rounded-2xl bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)] ring-1 ring-ink-200 overflow-hidden animate-fadeIn">
    <div class="flex items-center justify-between px-4 py-3 border-b border-ink-100 bg-ink-50/50">
      <div class="flex items-center gap-3">
        <div class="h-8 w-8 rounded-lg bg-white ring-1 ring-ink-200 grid place-items-center overflow-hidden"><img src="{{ asset('storage/web/favicon.ico') }}" alt="Logo" class="h-6 w-6 object-contain"></div>
        <div>
          <p class="text-sm font-extrabold text-ink-900 leading-none">Menu Pegawai</p>
          <p class="text-[11px] font-semibold tracking-widest text-ink-500 uppercase">PMPTSP • Aksi cepat</p>
        </div>
      </div>
      <button type="button" class="menu-left-btn-close h-8 w-8 rounded-full bg-white text-ink-600 grid place-items-center ring-1 ring-ink-200 hover:bg-ink-50"><i class="fa-solid fa-xmark text-xs"></i></button>
    </div>
    <div class="max-h-[70vh] overflow-y-auto p-3">
      @include('layouts.left-menu-nav')
    </div>
    <div class="p-3 border-t border-ink-100 bg-ink-50/30">
      <p class="text-xs text-ink-500 text-center">Tekan di luar untuk menutup • <span class="font-bold text-ink-700">Esc</span></p>
    </div>
  </div>
</div>

<script>
(function() {
  function initLeftMenu() {
    const container = document.getElementById('sideMenuLeftContainer');
    const backdrop = document.getElementById('sideMenuLeftBackdrop');
    if (!container) return;
    const openBtns = document.querySelectorAll('.btn-show-left-menu');
    const closeBtns = document.querySelectorAll('.menu-left-btn-close');
    const show = (e) => {
      if (e) e.preventDefault();
      container.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    };
    const hide = () => {
      container.classList.add('hidden');
      document.body.style.overflow = '';
    };
    openBtns.forEach(b => {
      b.removeEventListener('click', show);
      b.addEventListener('click', show);
    });
    closeBtns.forEach(b => {
      b.removeEventListener('click', hide);
      b.addEventListener('click', hide);
    });
    if (backdrop) {
      backdrop.removeEventListener('click', hide);
      backdrop.addEventListener('click', hide);
    }
    document.removeEventListener('keydown', hide);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !container.classList.contains('hidden')) hide(); });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLeftMenu);
  } else {
    initLeftMenu();
  }
  // also re-init after Livewire updates
  if (window.Livewire) {
    document.addEventListener('livewire:navigated', initLeftMenu);
  }
})();
</script>
