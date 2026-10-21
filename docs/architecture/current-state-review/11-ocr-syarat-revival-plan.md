# 11 — OCR Syarat (W9) Revival & Optimization Plan

Bagian dari *Current-State Architecture Review* Nestara — dokumen plan lanjutan (2026-10-20). W9 dipertahankan sebagai pipeline **image-only**. PDF eksplisit di luar scope.

**Keputusan:**
- W9 image-only. PDF di luar scope; tidak ada PDF ingestion.
- Model auth dokumen: **signed URL** (HMAC + TTL).
- Prefill `entri_data` dan perubahan security selesai.

---

## P0 — Validasi kebutuhan (gate)

| # | Aksi | Bukti/lokasi | Output | Status |
|---|---|---|---|---|
| 0.1 | Audit doc-type & kelayakan OCR image-only: volume dan kualitas sampel produksi | DB live + sampling dokumen | Konfirmasi kelayakan image-only | **PENDING — environment-dependent: production DB dan owner input** |
| 0.2 | Konfirmasi owner alur entri-data back-office dipakai | Q5/UQ-10 (`10` §3) | GO/NO-GO formal | ✅ GO (user approval) |
| 0.3 | Keputusan auth dokumen: signed URL vs push-upload | `OcrServiceImpl.php:51-80` (`getUrlDokumen`), D4 | Keputusan tertulis | ✅ Signed URL |

---

## P1 — Functional revival

| # | Aksi | Terkait | Effort | Status |
|---|---|---|---|---|
| 1.1 | Queue worker: service compose `queue:work` (dev) + catatan supervisor untuk prod; monitor `failed_jobs` | WEB-F05, D6 | 0.5 d | ✅ dev compose (`web-queue` service + `--tries=3 --max-time=3600`); worker host-side sudah **dieksekusi nyata** 2026-10-20: `queue:work database --stop-when-empty` → job `SendOcrRequest` RUNNING→DONE, audit row `ocr_requests` ter-tulis (status `failed` — vision menolak fetch file; lihat W-OPS1). prod supervisor = catatan di `docker-compose.yml`, PENDING. Note fallback: bila web compose pakai php `:8.4-cli`, driver `pdo_mysql` TIDAK ter-install di image bawaan docker-php — install-on-start `docker-php-ext-install pdo_mysql` atau custom image; DB grant untuk client `172.17.0.x` wajib (user MySQL localhost-only = worker container tolak `1130 Host not allowed`) |
| 1.2 | Verifikasi reachability vision→web (dev compose `ocr-api`→`web:8000`; prod UNKNOWN → tulis network policy) | UQ-2 | 0.5 d | ✅ dev E2E live (2026-10-20): vision (docker image, `--network host`) → `POST /api/ocr/do-ocr` (imageUrl = signed URL web `:8000`) → `WAITING` → **`DONE` 9 s**; rantai signed-URL (middleware → decrypt → `image/png` 1853 B HTTP 200) tervalidasi dari sisi container. Catatan: topologi dev-hidup wajar = `--network host` (bridge memblokir koneksi host-db from container, sesuai komentar compose vision); prod compose official perlu host alias `web:8000` in SSRF allowlist `OCR_FETCH_IMAGE_URL_ALLOWED_HOSTS`. Caveat: E2E memakai **image vision lama** (old image = sebelum batch 1b/2); build image baru butuh `mvn` di Docker (javac 17) → validasi kode vision baru PENDING di env dev user. ops-test ringkat di bawah |
| 1.3 | Signed URL `/api/files` (HMAC + TTL ~15 m) untuk fetch oleh vision & mobile; cek ownership via token | D4/WEB-F01/XR-04 | 1 d | ✅ `SignedFileUrl` (HMAC-SHA256 + hkdf APP_KEY, TTL 900 s); middleware `signed-file` di routes; emisi tercover: `DokumenPemohonResource.url_file`, `UserResource.foto_url`, `OcrServiceImpl::getUrlDokumen`; 14 unit + 7 feature test (unit 14 ✅, feature membutuhkan run client-side); deferred: `FileController::download` (rute web auth) belum di-sign |
| 1.4 | *(dibatalkan)* push-upload endpoint — digantikan oleh keputusan 0.3 | S-07 | — | — |
| 1.5 | Persist `OcrRequest` **sebelum** diproses + idempotency key `(pemohon, syarat, izin)` — anti-orphan & anti-duplicate saat job retry | CR-01, XR-07 | 1 d | ✅ web-side: persist-first `requestOcrForNewRegistration` di `PerizinanServiceImpl` (dipanggil setelah dokumen tersimpan; sekaligus fix bug dispatch-*sebelum*-file-save dari XR-07), state machine `pending/processing/done/failed/failed_final` + `isBlockedForResend()`, unique index `(izin_id, syarat_izin_id)`, job rethrow + `failed()` hook. Migration `2026_10_20_000001` **belum di-run** (butuh MySQL) |
| 1.6 | Multipart limit vision ≥ 4 MB | XR-01 | 0.1 d | ✅ `spring.servlet.multipart.max-file-size=4MB` / `max-request-size=6MB` (env-overridable) di `application.properties` |

**Kriteria keluar P1:** flow image-only E2E di environment valid: izin dibuat → queue job sukses → vision fetch signed URL → OCR sukses → `entri_data` ter-prefill.

## Status

Prefill implementation dan perubahan security selesai.

## Pending gates — environment-dependent only

- **P0.1 production audit**: production DB dan sampling image.
- **Feature tests**: jalankan dengan test DB valid.
- **Vision new-image build**: build dan validasi image vision baru.
- **Browser E2E**: verifikasi flow image-only sampai prefill.
- **Production topology**: tetapkan dan verifikasi network policy, worker, dan allowlist.
