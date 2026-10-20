# 06 — Security, Reliability & Operations

Bagian dari *Current-State Architecture Review* Nestara (2026-10-19). Skala severity: Critical / High / Medium / Low; confidence: High / Medium / Low. Semua temuan melengkapi register `08-architecture-risks-and-technical-debt.md` (ID identik).

---

## 1. Security

### 1.1 Peta auth boundary (aktif)

| Sisi | Kontrol | Catatan risiko |
|---|---|---|
| Mobile → Web | JWT Bearer (tymon/jwt-auth) TTL 30 hari / refresh 60 hari | Mobile menyimpan token plain di secure storage; **client-token endpoints tidak dipakai** (`XR-05`); mobile tidak memiliki interceptor / refresh mid-session (`MOB-H03`) |
| Web desktop | session database + Spatie roles (pemohon/front-office/back-office/admin); middleware `role:` di routes | Permission Spatie tidak dipakai (role-only); verifikasi email aktif (throttle 6/min di verify) |
| Web → Vision | header `X-API-KEY` statik (AES ter-encrypt di DB web) | API key vision tidak ada hashing/rotasi grace (`VIS-F19`); `isActive` tidak pernah dicek |
| Vision dashboard | formLogin session, timeout 1500m = **25 jam**, **CSRF disabled** | `VIS-F10` — tanpa token CSRF pada form dashboard/pasword |
| Vision verify code | `/account/verify` 6-digit tanpa rate limit & tanpa scope user | `VIS-F05` |

### 1.2 Temuan security berperingkat

| ID | Severity | Confidence | Temuan | Bukti |
|---|---|---|---|---|
| **S-01** (=`WEB-F01`) | **High** | High | **`GET /api/files/{b64}` publik** tanpa auth/ownership — dokumen persyaratan terenkripsi di-disk dapat di-unduh oleh siapa pun dengan file id (base64; id terkenal via resource DokumenPemohon `url_file`) | `routes/api.php:59-61` (di luar group jwt.auth), `FileController::render` |
| **S-02** (=`VIS-F03`) | **High** | High | **`keystore.jks` ter-commit di repo + password default `changeit`** untuk melindungi API key AES-ECB tanpa IV | keystore.jks (514 B), `ApiKeyGenerator.java:25`, `KeyStoreUtil.java:16`, Dockerfile:51-53 |
| **S-03** (=`WEB-F02`) | **High** | High | **`.env.testing` ter-track git** dengan kredensial MySQL riil (`ubuntudev`) + CI biasanya meng-commit `APP_KEY/JWT_SECRET` | `git ls-files .env.testing`, `.github/workflows/ci.yml` |
| **S-04** (=`VIS-F05`) | **High** | High | **Brute force verifikasi akun Vision**: 6-digit, tidak di-scope user, resend menambah kode valid baru tanpa invalidate kode lama, expiry 1 jam, tanpa rate limit | `AccountVerificationService:29-67`, `VerificationCodeRepository::findByCode` |
| **S-05** (=`VIS-F02`) | **High** | High | **`spring.servlet.multipart.*` tidak dikonfigurasi** → batas Spring default 1 MB menimpa kontrak 3 MB (`XR-01`) — dipindah di `03` juga | `application.properties`(absensi), `OcrDataRequest` |
| **S-06** | Medium | High | **`usesCleartextTraffic=true` untuk build RELEASE Android** — HTTP ke semua host di produksi | `android/app/src/main/AndroidManifest.xml:16-20` |
| **S-07** (=`VIS-F08`) | **High** | High | **SSRF**: vision `imageUrl` menerima URL bebas — `ImageIO.read(new URL(...))` tanpa whitelist host/outs | `OcrDataRequest:13`, `PyTesseractOcrService:29` |
| **S-08** (=`VIS-F04`) | High | High | **NPE → 500 + leak `getLocalizedMessage`** dari `ErrorController` | `OcrController:161-164,130`; `ErrorController:43-52` |
| **S-09** (=`WEB-F07`) | Medium | High | **Log token raw di `TokenController`** (`Log::info('request token',$token)`) + `FileController` log file name; retensi log di disk `app` tidak berbayang | `TokenController:19-21` |
| S-10 (=`WEB-F09`) | Medium | High | `cleanLivewireTmp` menghapus seluruh `storage/app/livewire-tmp` tiap upload — bisa merusak batch upload user lain (DoS primit) | `FileServiceImpl:71-75` |
| S-11 (=`WEB-F16`) | Low | Med | Race `no_pendaftaran` per hari tanpa lock — bisa duplicate nomor under concurrency | `Izin::boot` |
| S-12 | Medium | High | **Tidak ada CORS config** (`config/cors.php` tidak ada) — HandleCors default deny; ke titik tambahan bila SPA/experiences kecil web-site nantinya | absensi file, `bootstrap/app.php` |
| S-13 | Medium | High | Tidak ada **rate-limiter** pada `/auth/*` dan `/ocr/*` web (throttle global default Laravel 60/min per user); brute force login/OCR spam bisa jalan | tidak ada RateLimiter di `AuthController`; api middleware |
| S-14 (=`WEB-F03`) | Medium | High | Bug `PengaturanServiceImpl::find` memakai `id` bukan `user_id` — kegagalan setting per-user (termasuk OCR/API token) bila user_id menyimpang | `PengaturanServiceImpl:24-30` |
| S-15 (=`VIS-L`, `web` OCR) | Low | High | File KTP di vision: file tanpa sah volume di compose (hilang saat recreate) + **permanent retention** termasuk PII (`original_extracted_text`) | compose vision; `ocr_result` table; `uploads/ocr-request/` |
| S-16 | Low | Med | **File permissions/`KTP/` di workspace root** berisi 2 jpg (mode 0600) — fixture PII di luar repositori | listing dir `nest-nestara/KTP/` |

### 1.3 Kontrol yang sudah baik

- Dokumen persyaratan web **AES-at-rest + random filename** (`FileServiceImpl::storeEncryptedFile`) — hardening lebih baik dari umum.
- **Ownership check** pada polling `GET /api/ocr/ktp/{request_id}` (web) (`user_id`+`request_id`).
- Delete-account menghapus file KTP disk + token + sanctum cleanup (**hygiene bagus**).
- Vision `guardedExtract(Throwable)` menahan crash native per-engine — resilience kelas dunia.
- Retensi terminal: file KTP web dihapus di status `done/failed` (`OcrController.php:86-93`).
- Whitelist ext + bug-busuk renam at `OcrController::storeAndGetContents`.

## 2. Reliability

### 2.1 Timing pie chart

Jalur OCR KTP: web poll internal 8 s / vision sync budget 60 s / mobile deadline 120 s. Data: **mobile mengasumsikan 27–60 s** sebagai latensi normal pipeline (komentar `ocr_service.dart:29-30`), sedangkan web timeout do-ocr hanya **25 s**. Karena do-ocr di Vision efektif sinkron (`VIS-F01`), request dengan durasi 25–60 s **kalah bersanding dan gagal di server web** sebelum OCR selesai → skenario XR-02 (orphan + kuota terpakai). Perbaikan berdampak terbesar: betulkan `VIS-F01` (pindah `processingExtractionText` ke bean terpisah agar `@Async` efektif) sehingga do-ocr cepat respons `{requestId}` dan polling mengambil alih.

### 2.2 Temuan reliability per severity

| ID | Severity | Confidence | Temuan | Bukti |
|---|---|---|---|---|
| R-01 (=`VIS-F01`) | **High** | High | **`@Async` di-bypass oleh self-invocation** — OCR jalan di thread HTTP Tomcat sampai 60 s → risiko thread-exhaustion seiring request rate naik | `TextProcessorService.java:41-56,59-92`; `AppConfig.java:28-39` |
| R-02 (=`VIS-F09`) | **High** | High | **`process.waitFor()` tanpa timeout** di `PythonOcrExcecutor` → python hang bisa mengunci thread selamanya (tanpa watchdog) | `PythonOcrExcecutor.java:26-56` |
| R-03 (=`VIS-F02`: XR-01) | High | High | multipart limit default 1MB — foto KTP 2-5MB kena MaxUploadSizeExceeded —)] belakangan | properties |
| R-04 (=`VIS-F06`) | High | Medium | riwayat crash native JVM (3 `hs_err_pid*.log` SIGSEGV dari tess4j/JNA) — runtime guard(Throwable) tidak selalu menangkap SIGSEGV | hs_err logs; `EnsembleOcrService:94-127` |
| R-05 (=`VIS-F07`) | High | High | **File uploads tidak ber-Volume mount** dan tidak ada cleanup — KTP user pernah unggah bisa hilang saat recreate container / ter-akumulasi selamanya | `docker-compose.yml:22-83` (vision), `uploads/ocr-request/` |
| R-06 (=`WEB-F05`) | High | High | **Job `SendOcrRequest` tidak punya worker yang diberlakukan di compose/test** — queue database tumbuh tanpa proses; menjadikan W9 buliur di procedural workflow | compose (tidak ada `queue:work`), `PerizinalServiceImpl:73` |
| R-07 (=`WEB-F06`) | Medium | High | Sleep 2 s × polling 8 s di **thread HTTP PHP-FPM / artisan serve single-thread** — beberapa request ber-sama bisa mengunci web server | `OcrController.php:175-192` |
| R-08 (=`VIS-F13`) | Medium | High | Quota increment bermula **&** `isRequestAllowed` check-then-act tanpa lock → over-use; do-ocr sukses saja yang ter-affect | `RequestLimitService:22-43`; `OcrController:98` |
| R-09 (=`VIS-F11`) | Medium | High | Dedupe `OcrRequest` by `imageUrl` **tanpa client scope** → cross-tenant + duplicate processing | `OcrRequestServiceImpl:34-44` |
| R-10 (=`VIS-F12`) | Medium | High | `ExtractedTextServiceImpl::saveAll` memakai **reuse id baris pertama** untuk semua fields → sebar data saat result di-respon | `ExtractedTextServiceImpl:44-57` |
| R-11 (=`WEB-F03`) | Medium | High | `PengaturanServiceImpl::find` beda kolom `id` vs `user_id` — **breaks user settings** pada data baru | `PengaturanServiceImpl:24-30` |
| R-12 (=`VIS-F14`) | Medium | High | Definisi hari kuota UTC vs dashboard lokal | dua kode tz |
| R-13 | Medium | High | Tidak ada scheduling/queue/systemd/worker fix define di compose **dan** tidak diartikulasi script monitoring/log rotation | compose |
| R-14 | Low | Med | Inkonsistensi format error Vision (400/404-no-body/500) memaking web parsing tidak deterministik | `XR-03` |

## 3. Operations

### 3.1 Configuration precedence

**Web**: `.env` runtime > env dalam compose > `config/api-client.php` > **DB `pengaturan` (runtime, kemenang)** — lapisan 3-lapis sulit diprediksi kalau tidak di dokumentasi: saat `config:cache` diterapkan, fungsi `env()` malah kembali null (`WEB-F08`).

**Vision**: env keys `${VAR:default}` dari `application.properties`. Temuan kecil: placeholder memakai **dash vs underscore** (`${SPRING_JPA_HIBERNATE_DDL-AUTO}`, `${APP_API-KEY_LENGTH}`, `${PHYSICAL-DEVICE_CORE-POOL}`) — dip-tok env jalan (SPRING_JPA_HIBERNATE_DDL_AUTO) tidak matches → fallback default aktif (`VIS-F15`). Bukan masalah karena nilai default sama, tapi berbahaya saat hoodar env.

**Mobile**: semua URL/secret via `--dart-define`/`anvil.yaml`; tidak ada .env committed — bagus.

### 3.2 Migrasi & schema

- Web: `php artisan migrate --force` di compose; 22 migrations berurutan; tuning index 2026-08-21 dan FK-change `pemohon.user_id SET NULL` 2026-08-25 menunjukkan evolusi skema aktif; tidak ada mekanisme backup terdokumentasi.
- Vision: **ddl-auto=update tanpa Flyway/Liquibase** → schema bergantung state Hibernate; drift mungkin tapi belum ter-observasi.

### 3.3 Deployment & release

| Hal | Status | Evidence |
|---|---|---|
| artifacts komposisi | dev compose di parent + vision Dockerfile; TIDAK ada prod compose | `nest-nestara/docker-compose.yml`, docker-compose.workspace.yml |
| Mobile release | CI GitHub Actions + scripts/bump-version + Anvil; signing via env; **staging/prod domain masih placeholder** | `anvil.yaml:20-28` |
| Web release | tidak ada deploy.yml/pipeline app dev; **`config:cache` belum pernah dipakai** (confidence med: `env()` masih dipanggil) | repo |
| Pause/queue worker & scheduler | tidak ada | compose |
| Backups | TIDAK ADA evidence | — |
| Health check | web `/up` + `check-env.sh`; vision tidak ada health endpoint; compose healthcheck hanya DB | dok |
| Monitoring | log-file lokal; tidak ada metrics/tracing; vision graph chart di dashboard internal | — |

### 3.4 Runtime dependencies Vision

Dockerfile Vision menginstal `tesseract-ocr`, `python3`, dan pip packages `rapidocr-onnxruntime pyclipper shapely onnxruntime` (opsi paddle/dukungan EDSR via env). Tesseract datapath di-set via compose: `TESSERACT_DATAPATH=/usr/share/tesseract-ocr/5/tessdata/`.

## 4. Catatan operasional lain

- File `hs_err_pid*.log` di root repo vision adalah bukti JVM crash historis (SIGSEGV di `libtesseract.so recog_all_words` + JNA tmp lib) — rekam jejak kegagalan native saat dev (lihat R-04).
- Detail retensi file KTP web: `04-data-model-and-erd.md` §3.
- Vault/secrets: tidak ada secret manager; password vision di env compose; `APP_KEY` web di `.env` dev — semua layak dipindah (09 D8).
