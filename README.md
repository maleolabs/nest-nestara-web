# Nestara — Sistem Perizinan Online DPMPTSP Kabupaten Bone Bolango

<p align="center">
  <img src="https://raw.githubusercontent.com/maleolabs/nest-nestara-web/main/storage/app/public/web/building.png" width="120" alt="Nestara Logo" />
</p>

<p align="center">
  <a href="https://github.com/maleolabs/nest-nestara-web/actions/workflows/ci.yml"><img src="https://github.com/maleolabs/nest-nestara-web/actions/workflows/ci.yml/badge.svg" alt="CI"></a>
  <a href="https://github.com/maleolabs/nest-nestara-web/actions/workflows/release.yml"><img src="https://github.com/maleolabs/nest-nestara-web/actions/workflows/release.yml/badge.svg" alt="Release"></a>
  <a href="https://github.com/maleolabs/nest-nestara-web/releases"><img src="https://img.shields.io/github/v/release/maleolabs/nest-nestara-web?label=release" alt="Release"></a>
  <a href="./LICENSE"><img src="https://img.shields.io/badge/license-proprietary-red.svg" alt="License"></a>
  <img src="https://img.shields.io/badge/PHP-8.4-777BB4?logo=php" alt="PHP 8.4" />
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel" alt="Laravel 13" />
</p>

<p align="center">
  Sistem Informasi Perizinan Online yang transparan, cepat, dan terintegrasi — untuk pelayanan publik DPMPTSP Kabupaten Bone Bolango.
</p>

---

## Tentang Nestara

Nestara adalah platform perizinan online end-to-end untuk Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kabupaten Bone Bolango. Pemohon dapat mengajukan izin, melengkapi dokumen, memantau status, hingga menerima dokumen perizinan secara digital — sementara petugas memverifikasi, memproses, dan menerbitkan izin dalam satu sistem terpadu.

Dibangun untuk **kecepatan, akuntabilitas, dan kepastian hukum** — dengan alur yang jelas, jejak audit lengkap, dan integrasi dokumen yang mulus.

### Ekosistem Nestara

| Produk | Deskripsi | Stack | Repo |
|--------|-----------|-------|------|
| **nestara-web** (repo ini) | Aplikasi web utama — portal pemohon + back-office petugas/admin | Laravel 13, Livewire 3, Tailwind 4 | `maleolabs/nest-nestara-web` |
| **nestara-vision** | OCR SaaS — ekstraksi teks KTP & dokumen umum via REST API (ensemble Tesseract + PaddleOCR, quality gate, preprocessing) | Spring Boot 3, Java 21, Python | `maleolabs/nestara-vision-saas` |
| **nestara-mobile** | Aplikasi mobile untuk pemohon — ajukan izin, lacak status, notifikasi | Flutter 3.5+ | `maleolabs/nest-nestara/nestara-mobile` |

```
Pemohon (Web / Mobile) ──► nestara-web (API + Livewire)
                               │
                               ├──► nestara-vision (OCR KTP/dokumen)
                               ├──► Storage (dokumen persyaratan)
                               └──► Back-office (verifikasi, penerbitan, laporan)
```

---

## Fitur Utama

### Perizinan & Layanan Publik
- **Katalog Jenis Perizinan** — daftar izin beserta persyaratan dokumen yang dapat dikonfigurasi per jenis
- **Pengajuan Online Bertahap** — form multi-step dengan validasi, upload dokumen, dan simpan draft
- **Pelacakan Status** — pemohon memantau progres pengajuan secara real-time (diajukan → verifikasi → diproses → diterbitkan/ditolak)
- **Manajemen Dokumen** — upload, validasi, preview, dan penyimpanan dokumen persyaratan; storage terisolasi per pengguna
- **Penerbitan Dokumen** — generate dokumen izin final (PDF) dengan QR code & template resmi

### Pemohon & Profil
- Registrasi & autentikasi (JWT + Sanctum), manajemen profil, ganti password
- Data pemohon terstruktur (perorangan/badan usaha) dengan normalisasi alamat wilayah (provinsi → desa via Emsifa API)
- Riwayat perizinan & unduhan dokumen

### Back-Office & Operasional
- Dashboard petugas — antrian verifikasi, statistik, dan monitoring
- **Peran & Hak Akses** — Spatie Permission (pemohon, petugas verifikasi, admin, super admin)
- **Data Entri Dinamis** — form entri dikonfigurasi per jenis perizinan, mendukung berbagai skema input
- **Ekspor Laporan** — Excel (Maatwebsite) & PDF (DomPDF) untuk rekap perizinan
- **Jejak Audit** — pencatatan aktivitas penting untuk akuntabilitas

### Integrasi & Otomasi
- **OCR KTP/Dokumen** — integrasi `nestara-vision` untuk ekstraksi otomatis data KTP dan dokumen pendukung (preprocessing, ensemble engine, fuzzy matching NIK)
- **Wilayah Indonesia** — API Emsifa untuk data provinsi/kabupaten/kecamatan/desa dengan cache lokal (`storage/app/region`)
- **QR Code** — generate & verifikasi QR untuk dokumen izin
- **Notifikasi** — sistem notifikasi internal untuk update status pengajuan

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Bahasa | PHP 8.4 |
| Framework | Laravel 13, Livewire 3.8 |
| Auth | Laravel Sanctum 4.3, Tymon JWT-Auth 2.3, Laravel UI |
| Frontend | Vite 5, Tailwind CSS 4.3, Bootstrap 5.3, Sass |
| Database | MySQL 8.0 (prod), SQLite (test) |
| Permission | Spatie Laravel-Permission 6.9 |
| Dokumen | Barryvdh DomPDF 3.1, Maatwebsite Excel 3.1, Endroid QR Code 6.0 |
| OCR | Nestara Vision SaaS (Spring Boot + Python ensemble) |
| DevOps | Anvil CLI v2.2.1, GitHub Actions, Immutable Artifact |
| Testing | PHPUnit 12, Laravel Pint |

---

## Panduan Cepat

### Prasyarat
- PHP 8.4+, Composer 2
- Node.js 22+, npm
- MySQL 8.0 (atau SQLite untuk test)
- (Opsional) Anvil CLI v2.2.1 — untuk build artifact & deploy

### Instalasi Development

```bash
# 1. Clone
git clone https://github.com/maleolabs/nest-nestara-web.git
cd nest-nestara-web

# 2. Dependensi
composer install
npm ci

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Database
php artisan migrate
php artisan db:seed   # jika seeder tersedia

# 5. Jalankan
composer run dev      # atau: npm run dev + php artisan serve (terminal terpisah)
# Buka http://localhost:8000
```

Konfigurasi OCR (opsional, di `.env`):
```env
OCR_API_BASE_URL="http://localhost:8080/api/"
OCR_REQUEST_RETRY_MAX=3
OCR_REQUEST_RETRY_DELAY=3
APP_OCR_SETTING=false
```

### Anvil — Build & CI Lokal

```bash
# Install Anvil (sekali)
curl -fsSL https://github.com/maleolabs/anvil/releases/download/v2.2.1/install.sh -o /tmp/anvil-install.sh
sh /tmp/anvil-install.sh --version v2.2.1

# Validasi konfigurasi
anvil config validate
anvil project status

# CI pipeline (butuh MySQL running + .env.testing)
anvil pipeline ci          # prepare-env → migrate → test (Unit)

# Build production
anvil pipeline build       # composer no-dev + npm build + config/route/view cache

# Package & verify artifact
anvil project version generate   # generate VERSION dari anvil.yaml
anvil artifact package
anvil artifact verify .anvil/artifacts/<artifact>.tar.gz
```

---

## Download & Instalasi via GitHub Release

Semua rilis dipublikasikan sebagai **GitHub Releases** dengan artifact immutable (`.tar.gz` + `.zip`).

| Channel | Branch sumber | Tag | Jenis | Cara dapat |
|---------|---------------|-----|-------|------------|
| **Candidate** | `staging` push | `v1.0.0-rc.N` | Pre-release | Otomatis terbit saat push ke `staging` |
| **Stable** | `main` promote | `v1.0.0` | Stable | Manual: Actions → Release → `workflow_dispatch` → `promote=true` |

### Unduh Artifact

```bash
# Via gh CLI
gh release download v1.0.0-rc.3 --pattern "artifact-*.tar.gz" --dir /tmp

# Atau via browser: https://github.com/maleolabs/nest-nestara-web/releases
```

### Instalasi di Server (Anvil Runtime)

```bash
# 1. Install Anvil di server (sekali)
curl -fsSL https://github.com/maleolabs/anvil/releases/download/v2.2.1/install.sh -o /tmp/anvil-install.sh
sh /tmp/anvil-install.sh --version v2.2.1

# 2. Init runtime & registrasi project (sekali)
anvil server init
anvil server project register \
  --project-id nestara-web \
  --install-root /srv/apps/nestara-web \
  --non-interactive

# 3. Install & activate release dari artifact
anvil server release install nestara-web /path/to/artifact-*.tar.gz
anvil server release activate nestara-web <release-id>

# 4. Verifikasi
anvil server status nestara-web
```

> **Deploy otomatis via GitHub Actions** juga tersedia (`deploy.yml`): `release published prerelease` → auto-deploy `staging`; `workflow_dispatch environment=production + release_tag` → deploy produksi (butuh `vars.DEPLOY_*_ENABLED=true` & `secrets.SSH_*`).

---

## Versi & Metadata

Versi aplikasi adalah **single source of truth** di `anvil.yaml` (`project:` section) dan di-materialize ke file `VERSION` untuk konsumsi runtime.

```yaml
# anvil.yaml
project:
  name: nestara-web
  product_name: Nestara
  version: 1.0.0
  release: Genesis
  build: 3
  status: alpha
  organization_name: DPMPTSP Kabupaten Bone Bolango
  # ... + website, copyright, minimum_*
```

```bash
anvil project version generate   # tulis VERSION (KEY=VALUE, uppercase)
cat VERSION                      # dibaca App\Support\Version di runtime
php artisan tinker --execute="echo Version::fullVersion();"
# → Nestara v1.0.0 Genesis
```

Facade `Version::productName()`, `organizationName()`, `organizationEmail()` dll digunakan di seluruh Blade — tidak ada hardcode identitas organisasi di view.

---

## Alur Kerja & Branching

```
develop  ──►  staging  ──►  main
  (CI)       (candidate)    (stable)
```

| Branch | Trigger | Hasil |
|--------|---------|-------|
| `develop` push / PR→`develop/staging/main` | `ci.yml` — `anvil pipeline ci` (unit tests) |
| `staging` push | `release.yml` candidate — test → `anvil artifact package+verify` → `vX.Y.Z-rc.N` pre-release |
| `main` `workflow_dispatch promote` | `release.yml` promote — reuse artifact candidate → `vX.Y.Z` stable |
| `release published` / `dispatch` | `deploy.yml` — download artifact → `anvil deployment upload` → `server release install/activate` |

Default branch: **`main`**.

---

## API

Base URL: `/api` (JWT, prefix `jwt.auth`)

| Grup | Endpoint | Deskripsi |
|------|----------|-----------|
| `POST /api/auth/register` | Registrasi |
| `GET /api/jenis-perizinan` | List katalog izin |
| `GET /api/jenis-perizinan/{id}/persyaratan` | Persyaratan per izin |
| `GET /api/izin` `POST /api/izin` | List & buat pengajuan |
| `GET /api/izin/{id}` `PATCH /api/izin/{id}/cancel` | Detail & batalkan |
| `POST /api/pemohon` `GET /api/pemohon` | Data pemohon |
| `GET /api/address/regions/{type}/{parent}` | Wilayah (Emsifa, cache lokal) |
| `GET /api/data-entri/{jenis_id}` | Data entri dinamis |
| `GET /api/files/{name}` | Render dokumen |

Dokumentasi lengkap menyusul (OpenAPI).

---

## Struktur Proyek

```
anvil.yaml                 # metadata versi & artifact config
VERSION                    # runtime metadata (generated)
.anvil/pipelines/          # build.yaml (prod build) + ci.yaml (test)
app/
  Http/Controllers/{Api,Admin,BackOffice}
  Livewire/{Admin,Dashboard,User}
  Services/DomainServices
  Support/Version          # VERSION parser & facade
resources/views/           # Blade + Livewire (identitas via Version::)
storage/app/public/web/    # asset publik (building.png, favicon.ico)
routes/{web,api}.php
tests/Unit/Support/Version # unit test metadata
.github/workflows/         # ci / release / deploy
```

---

## Keamanan

Laporkan kerentanan ke **legal@maleolabs.com**. Jangan buat issue publik untuk laporan keamanan.

---

## Lisensi

Proprietary — lihat [`LICENSE`](./LICENSE). Hak cipta © 2026 M2Code - MaleoLabs. Dilarang menyalin, mendistribusikan, atau memodifikasi tanpa izin tertulis.

---

<p align="center">
  Dibuat dengan ❤️ oleh <a href="https://maleolabs.com">M2Code - MaleoLabs</a> untuk DPMPTSP Kabupaten Bone Bolango.
</p>
