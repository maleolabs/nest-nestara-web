import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import Alpine from 'alpinejs';
import '../icons/fontawesome-free-6.4.2-web/css/all.min.css';
import '../../node_modules/pdfjs-dist/build/pdf.min.mjs';
import '../../node_modules/pdfjs-dist/build/pdf.worker.min.mjs';

import '@material/web/progress/linear-progress.js';

window.Alpine = Alpine;
Alpine.start();

// modern interactions
document.addEventListener('DOMContentLoaded', () => {
  // mobile menu toggle (tailwind)
  const btn = document.getElementById('mobileMenuBtn');
  const menu = document.getElementById('mobileMenu');
  if (btn && menu) {
    btn.addEventListener('click', () => {
      menu.classList.toggle('hidden');
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!expanded));
    });
  }
  // reveal on scroll
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('animate-fadeIn');
        observer.unobserve(e.target);
      }
    });
  }, { threshold: 0.12 });
  document.querySelectorAll('[data-reveal]').forEach(el => observer.observe(el));
});
