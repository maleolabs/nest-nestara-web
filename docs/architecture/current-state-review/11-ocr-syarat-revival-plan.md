# 11 — OCR Syarat (W9) Revival & Optimization Plan

Bagian dari *Current-State Architecture Review* Nestara — dokumen plan lanjutan (2026-10-20). Berbeda dengan 01–10 yang netral/current-state, dokumen ini **forward-looking**: mempertahankan dan mengoptimalkan pipeline OCR dokumen persyaratan izin (legacy W9). Status keputusan: user menyetujui arah "dipertahankan + optimalkan" (bertolak belakang dengan skenario drop di D10/D6), sehingga item yang sebelumnya "kandidat" menjadi **wajib di jalur kritis**: D4 (signed URL), D6 (worker/topologi), S-07 (SSRF mitigation), XR-01 (multipart limit).

**Keputusan P0 yang sudah diambil:**
- P0.2 (owner GO): ✅ diambil oleh user saat approval plan — 2026-10-20.
- P0.3 (model auth dokumen): ✅ **signed URL** (HMAC + TTL), sesuai rekomendasi D4. Alternatif push-upload dibatalkan kecuali di-revisi eksplisit.
- P0.1 (audit data kelayakan OCR): ⏳ PENDING — butuh live DB (`syarat_izin`, sampling `dokumen_pemohon`) dan owner input; tidak bisa dieksekusi dari sandbox (MySQL live tidak tersedia, lihat `10-evidence-and-open-questions.md` §5). **Bila hasil audit = mayoritas scan buruk → re-evaluasi keputusan.**

---

## P0 — Validasi kebutuhan (gate)

| # | Aksi | Bukti/lokasi | Output | Status |
|---|---|---|---|---|
| 0.1 | Audit doc-type & kelayakan OCR: jenis_perizinan dengan syarat `img`, volume, kualitas sampel | DB live + sampling dokumen | Daftar doc-type + GO feasibility | PENDING (butuh DB) |
| 0.2 | Konfirmasi owner alur entri-data back-office dipakai | Q5/UQ-10 (`10` §3) | GO/NO-GO formal | ✅ GO (user approval) |
| 0.3 | Keputusan auth dokumen: signed URL vs push-upload | `OcrServiceImpl.php:51-80` (`getUrlDokumen`), D4 | Keputusan tertulis | ✅ Signed URL |

---

## P1 — Functional revival

| # | Aksi | Terkait | Effort | Status |
|---|---|---|---|---|
| 1.1 | Queue worker: service compose `queue:work` (dev) + catatan supervisor untuk prod; monitor `failed_jobs` | WEB-F05, D6 | 0.5 d | ✅ dev compose (`web-queue` service + `--tries=3 --max-time=3600`); prod supervisor = catatan di `docker-compose.yml`, PENDING |
| 1.2 | Verifikasi reachability vision→web (dev compose `ocr-api`→`web:8000`; prod UNKNOWN → tulis network policy) | UQ-2 | 0.5 d | pending |
| 1.3 | Signed URL `/api/files` (HMAC + TTL ~15 m) untuk fetch oleh vision & mobile; cek ownership via token | D4/WEB-F01/XR-04 | 1 d | ✅ `SignedFileUrl` (HMAC-SHA256 + hkdf APP_KEY, TTL 900 s); middleware `signed-file` di routes; emisi tercover: `DokumenPemohonResource.url_file`, `UserResource.foto_url`, `OcrServiceImpl::getUrlDokumen`; 14 unit + 7 feature test (unit 14 ✅, feature membutuhkan run client-side); deferred: `FileController::download` (rute web auth) belum di-sign |
| 1.4 | *(dibatalkan)* push-upload endpoint — digantikan oleh keputusan 0.3 | S-07 | — | — |
| 1.5 | Persist `OcrRequest` **sebelum** diproses + idempotency key `(pemohon, syarat, izin)` — anti-orphan & anti-duplicate saat job retry | CR-01, XR-07 | 1 d | ✅ web-side: persist-first `requestOcrForNewRegistration` di `PerizinanServiceImpl` (dipanggil setelah dokumen tersimpan; sekaligus fix bug dispatch-*sebelum*-file-save dari XR-07), state machine `pending/processing/done/failed/failed_final` + `isBlockedForResend()`, unique index `(izin_id, syarat_izin_id)`, job rethrow + `failed()` hook. Migration `2026_10_20_000001` **belum di-run** (butuh MySQL) |
| 1.6 | Multipart limit vision ≥ 4 MB | XR-01 | 0.1 d | ✅ `spring.servlet.multipart.max-file-size=4MB` / `max-request-size=6MB` (env-overridable) di `application.properties` |

**Kriteria keluar P1:** flow E2E di dev: izin dibuat → queue job sukses → vision fetch dokumen (signed URL) → OCR sukses → `entri_data` ter-prefill di back-office.

## P2 — Security & data-integrity hardening

| # | Aksi | Terkait | Status |
|---|---|---|---|
| 2.1 | SSRF guard pada `imageUrl` (whitelist host + https + blacklist IP internal) | S-07/VIS-F08 | pending |
| 2.2 | Standardisasi error-shape vision (ApiResponse; tanpa `getLocalizedMessage`; 404 berbody) | XR-03/VIS-F04 | pending |
| 2.3 | Fix `PengaturanServiceImpl::find` (kolom `user_id`, bukan `id`) + regression test | WEB-F03 | ✅ `find` → per-user override + fallback global (`user_id IS NULL`, per-user menang); audit caller: tidak ada yang kompensasi bug; test `PengaturanFindTest` (requires DB) ditulis |
| 2.4 | Retensi PII jalur dokumen (retention + cleanup scheduler + volume mount `uploads/`) | CR-02/VIS-F07/D5 | pending |
| 2.5 | Kuota: fix dedupe scope-client, tentukan apakah doc-OCR mengonsumsi kuota, normalisasi WIB | XR-07/VIS-F13/D9 | pending |
| 2.6 | Fix `@Async` self-invocation (VIS-F01) + `process.waitFor` timeout (VIS-F09) | VIS-F01/VIS-F09 | pending |
| 2.7 | Rate-limit verifikasi akun vision + `hash_equals` | VIS-F05 | pending |

## P3 — Optimization (urut ROI)

1. Non-blocking back-office UI progress + php-fpm (stop retry-loop sync `OcrServiceImpl` — WEB-F06).
2. Paralelisasi per-dokumen + batch-status endpoint.
3. Akurasi: kalibrasi PSM/conf per doc-type; target akurasi field dari hasil audit P0.1.
4. Kontrak formal `extractedTexts` (OpenAPI atau consumer-driven contract test — D2); standarkan list-vs-map.
5. Observability: `request_id` correlation, metrik vision, health endpoint (R-13).

## P4 — Test & rollout

1. Smoke test pipeline vision (VIS-F18): ≥2 test fungsional (pipeline KTP + doc persyaratan).
2. Feature test web: `SendOcrRequest` idempotency, retry semantics, tidak merusak `no_pendaftaran` (S-11).
3. Contract test D2 (web + vision).
4. Rollout bertahap dev → staging → prod; tiap stage verifikasi UQ-2 + TTL signed URL + UX entri-data.

## Jadwal

| Fase | Effort | Status |
|---|---|---|
| P0 | 0.5–1 d | parsial (0.2, 0.3 ✅; 0.1 pending DB) |
| P1 | 3–4 d | dimulai |
| P2 | 4–5 d | dimulai (2.3) |
| P3 | 3–5 d | belum |
| P4 | 2–3 d | belum |

Total ±2–3 minggu efektif setelah P0 complete. Payback: sebagian besar effort P1/P2 (D4, D6, XR-01) adalah infrastruktur yang juga dikonsumsi jalur KTP aktif — bukan sunk cost W9 saja.

## Risiko utama

| Risiko | Mitigasi | Fase |
|---|---|---|
| Input tidak OCR-viable → ROI nol | Gate P0.1 | P0 |
| Reachability prod vision→web | network policy P1.2 | P1 |
| SSRF & `/files` publik | signed URL (P1.3) + whitelist (P2.1) | P1/P2 |
| Orphan & duplicate kuota | persist-first + idempotency (P1.5) | P1/P2 |
| Thread exhaustion vision | VIS-F01 fix (P2.6, wajib sebelum traffic naik) | P2 |
| Contract drift | contract test (P3.4) | P3 |
