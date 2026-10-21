# 10 — Evidence Register & Open Questions

Bagian dari *Current-State Architecture Review* Nestara (2026-10-19). Dokumen ini adalah register bukti dan pertanyaan terbuka untuk semua klaim yang dibuat di dokumen 01–09. Setiap klaim dirujuk dengan ID skema stabil yang sama (XR, CR, WEB-F, VIS-F, MOB-*, S-, R-, M-, D*, W*).

## 1. Klasifikasi status bukti

| Status | Makna |
|---|---|
| **VERIFIED** | Kode/konfigurasi dibaca langsung di sumber (file : line, symbol), atau perintah benar-benar dieksekusi dengan output terekam. |
| **INFERRED** | Kesimpulan logis dari bukti terverifikasi, belum diuji di runtime. |
| **UNRESOLVED** | Tidak dapat ditentukan dari kode saja; butuh akses runtime/prod atau jawaban owner. |

Semua temuan di register 08 mengikuti aturan ini. Item di bawah adalah klaim kunci + status.

## 2. Bukti klaim utama (VERIFIED)

### 2.1 Lintas-repo

| ID | Klaim | Bukti | Status |
|---|---|---|---|
| XR-01 | Limit multipart Vision default 1 MB vs kontrak 3 MB | `nestara-vision-saas/src/main/resources/application.properties` — **tidak ada** `spring.servlet.multipart.*`; validator app 3 MB di `OcrDataRequest` `@FileSize`; upload sampel dev di `uploads/ocr-request/` ≤ 566 KB (dev belum kena limit). Spot-check grep manual dilakukan. | VERIFIED (config) / INFERRED (runtime limit 1 MB mengikuti default Spring Boot 3.3.3) |
| XR-02 | `do-ocr` efektif sinkron 60 s sementara web timeout 25 s | `TextProcessorService.java:41-56,59-92` (self-invocation `processingExtractionText`), `AppConfig.java:28-39` (`@Async` config terpasang; tidak efektif karena self-invocation); web timeout 25 s di `OcrAPIServiceImpl2`; mobile deadline 120 s (`ocr_service.dart:29-30`). | VERIFIED (kode) / INFERRED (timing runtime berbasis kode path) |
| XR-03 | Error-shape Vision tidak konsisten + leak pesan internal | `OcrController.java:53-58,131-134,161-175` (404 no-body, 200-varying shape); `ErrorController.java:43-52` (`getLocalizedMessage()` balikan ke client). | VERIFIED |
| XR-04 | Legacy izin-OCR (W9) bergantung pada `/files/{b64}` publik + reachability vision→web + queue worker | `SendOcrRequest::dispatch` di `PerizinanServiceImpl.php:73`; `route('api.render-file')` di `OcrServiceImpl::getUrlDokumen`; compose tanpa service `queue:work`; web `.env` OCR base-url `http://ocr-api:8080/api/` di dev compose. | VERIFIED (kode/compose) |
| XR-05 | `/verify-client-token` & `/generate-client-token` tidak dikonsumsi mobile; implementasi punya bug | grep mobile = 0 referensi; `ClientTokenService.php:139-174` (`!==` vs `hash_equals`; counter tidak pernah naik); `TokenController.php:19-21` log raw token. | VERIFIED |
| XR-06 | Tanpa spec kontrak lintas-repo | tidak ada OpenAPI/typed-client di ketiga repo; duplikasi manual: `ktp_ocr_result` shape diweb `KtpFieldMapper.php:192-194`, mobile `_extractList` menangani 3 bentuk response. | VERIFIED |
| XR-07 | Dedupe `OcrRequest` by `imageUrl` tanpa client-scope | `OcrRequestServiceImpl.java:34-44` (`findByImageUrl` tanpa clientId). | VERIFIED |
| CR-01 | Orphan OCR saat do-ocr timeout | `OcrController.php:175-192` (polling 2 s × 8 s, persist `request_id` hanya saat 200 sukses). Konsekuensi logis XR-02. | VERIFIED (kode) / INFERRED (runtime) |
| CR-02 | Retensi PII asimetris | web: file KTP dihapus saat terminal status (`OcrController.php:86-93`); vision: `original_extracted_text` tersimpan tanpa cleanup + `uploads/ocr-request/` akumulasi permanen; compose vision tanpa volume mount. | VERIFIED |

### 2.2 Web (WEB-F / S- / R- web)

| ID | Bukti inti | Status |
|---|---|---|
| WEB-F01 (`S-01`) | `routes/api.php:59-61` — `GET /api/files/{b64}` di luar group jwt.auth; `FileController::render` tanpa ownership check | VERIFIED |
| WEB-F02 (`S-03`) | `git ls-files .env.testing` → tracked; memuat kredensial MySQL riil; `.github/workflows/ci.yml` | VERIFIED |
| WEB-F03 (`S-14`, R-11) | `PengaturanServiceImpl.php:24-30` — `find` memakai kolom `id` bukan `user_id` | VERIFIED |
| WEB-F04 (`S-13`) | Tidak ada `RateLimiter` pada auth/OCR API (grep middleware) | VERIFIED |
| WEB-F05 (`R-06`) | compose (`docker-compose.yml:100-127`) — web = php:8.4-cli + `artisan serve`; tidak ada process worker; `QUEUE_CONNECTION=database` + `SendOcrRequest` job | VERIFIED |
| WEB-F06 (`R-07`) | `OcrController.php:175-192` — sleep 2 s × 8 iterasi di thread HTTP (artisan serve single-thread di dev) | VERIFIED (kode) / INFERRED (deployment prod UNKNOWN) |
| WEB-F07 (`S-09`) | `TokenController.php:19-21` raw token di log | VERIFIED |
| WEB-F08 | 3 lapis OCR config + `env()` di runtime (`Pengaturan.php:22`, `OcrAPIServiceImpl2`) | VERIFIED |
| WEB-F09 (`S-10`) | `FileServiceImpl.php:71-75` — `cleanLivewireTmp` menghapus seluruh directory tmp | VERIFIED |
| WEB-F10 (`M-*`, dead code) | Sanctum declared-unused (`composer.json`, zero Sanctum guard aktif), `maatwebsite/excel` + `app/Imports/Perizinan/*` tidak terpanggil route, `OcrAPIServiceImpl` deprecated | VERIFIED |

### 2.3 Vision (VIS-F / S- / R- vision)

| ID | Bukti inti | Status |
|---|---|---|
| VIS-F01 (`R-01`) | `TextProcessorService.java:41-56,59-92`, `OcrProcessorService.java:68-71`, `AppConfig.java:28-39` | VERIFIED |
| VIS-F03 (`S-02`) | `keystore.jks` di-commit (±514 B), `ApiKeyGenerator.java:25` (password default), `KeyStoreUtil.java:16`, AES-ECB tanpa IV | VERIFIED |
| VIS-F04 (`S-08`) | `OcrController.java:161-164` NPE path; `ErrorController.java:43-52` | VERIFIED |
| VIS-F05 (`S-04`) | `AccountVerificationService.java:29-67` — 6-digit code, resend tidak invalidate lama, tanpa rate-limit; repo `findByCode` | VERIFIED |
| VIS-F07 (`R-05`, `S-15`) | compose vision tanpa volume `uploads/`; `TesseractOcrService.java:305-310` temp-file leak; `FileManagerService.java:67-82` | VERIFIED (config) |
| VIS-F08 (`S-07`) | `OcrDataRequest.java:13` (imageUrl bebas), `PyTesseractOcrService.java:29` (`ImageIO.read(new URL(...))`) | VERIFIED |
| VIS-F09 (`R-02`) | `PythonOcrExcecutor.java:26-56` — `process.waitFor()` tanpa timeout | VERIFIED |
| VIS-F14 (`R-12`) | Kuota harian dihitung UTC (`RequestLimitService.java:22-43`) vs dashboard pakai systemDefault + `CONVERT_TZ(+07:00)` | VERIFIED |
| VIS-F15 | Placeholder dash vs underscore di `application.properties:17,48-58` (`${SPRING_JPA_HIBERNATE_DDL-AUTO}` dsb.) — env override tidak match → fallback silent | VERIFIED (nama key) / INFERRED (efek runtime saat override) |
| VIS-F16 (`M-07`) | Dead-code cluster: WebSocket `/ocr-ws` dead, Caffeine zero `@Cacheable`, LLM stub, Paddle disabled, `ExternalFileUploader` stub | VERIFIED (grep) |
| VIS-F18 | `OcrProcessorServiceTest.testProcessOcr_Success()` dan `TesseractOcrServiceTest.extractTextFromImageTest()` — **body kosong**; historical surefire pass dari stub | VERIFIED |
| R-04 (`VIS-F06`) | 3 `hs_err_pid*.log` di root repo vision (SIGSEGV `libtesseract.so` + JNA tmp lib) | VERIFIED (file ada, dibaca) / INFERRED (crash rate tidak diketahui) |

### 2.4 Mobile (MOB-)

| ID | Bukti inti | Status |
|---|---|---|
| MOB-H01 | `model/user.dart:8-114` (`UserState` mock lokal dimuat `set_profil.dart`, `profilpage.dart`) — profil tidak diambil dari `/auth/me` + `/pemohon` | VERIFIED |
| MOB-H02 (=S-06) | `android/app/src/main/AndroidManifest.xml:16-20` — `android:usesCleartextTraffic="true"` di manifest utama (aktif pada build release juga, bukan overlay debug) | VERIFIED |
| MOB-H03 | `izin_service.dart:249-276` dll — 401 tidak ada interceptor auto-refresh (Dio manual, tiap service error-map sendiri) | VERIFIED |
| MOB-H04, M-02, M-06 | 3 paradigma state (setState/GetX/ValueNotifier+prefs); Retrofit vs Dio; `pubspec.yaml` `name: b`; 12 deps tanpa import (grep per-dep) | VERIFIED |
| MOB-L01 | iOS `Info.plist` — permission string & ATS exemption belum siap; iOS build tidak dijalankan | VERIFIED (config) / UNRESOLVED (runtime build) |
| MOB-L05 | `lengkapi_profil_page.dart:451` — koreksi typo OCR sederhana (contoh `GORGNTALG`) | VERIFIED |

### 2.5 Hasil eksekusi (VERIFIED via run)

| Perintah | Repo | Hasil 2026-10-19 |
|---|---|---|
| `flutter test` | mobile | 47/47 passed; `git status` bersih (tidak mutasi source) |
| `flutter analyze` | mobile | No issues |
| `php artisan test` | web | 37 passed / 17 failed — 16 failure = env (MySQL sandbox tidak ada), 1 failure logis `KtpFieldMapperTest` (kapitalisasi `Jawa Timur`) |
| `vendor/bin/pint --test` | web | 223 file pending formatter |
| `git ls-files .env.testing` | web | tracked |
| `php -r class_exists(EventServiceProvider)` | web | false (dead ref `bootstrap/providers.php:4`) |
| grep per-dep pubspec | mobile | 12 deps zero import |

## 3. Tidak dapat diverifikasi dari kode (UNRESOLVED)

| # | Item | Mengapa | Dampak ke review |
|---|---|---|---|
| UQ-1 | **Topologi deployment produksi** (hosting web, vision, DB; nginx/php-fpm vs artisan serve; worker/scheduler/backup) | Tidak ada artefak prod di ketiga repo; hanya dev compose | D6 dibuat sebagai pertanyaan, bukan kesimpulan. Semua klaim ops prod berlabel UNKNOWN |
| UQ-2 | **Reachability jaringan vision→web** (`/api/files` fetch saat pipeline legacy W9) | Tidak diuji runtime; hanya terbaca dari kode `OcrServiceImpl::getUrlDokumen` | XR-04 status "efektif tidak berjalan di dev" didasarkan queue worker tidak ada + reachability belum terbukti — bukan penegasan runtime |
| UQ-3 | `perizinan-dpmptsp.api.base-url` hanya terpakai di test | Hanya referensi = config + test; tidak ada runtime call path aktif yang ditemukan | Dinilai dead/legacy — perlu konfirmasi owner sebelum dihapus |
| UQ-4 | **Live DB state** (isi tabel riil: kuota terpakai, orphan `ocr_ktp_requests`, file uploads) | Review read-only; tidak melakukan query DB | CR-01/CR-02 adalah analys kode, distribusi data riil tidak dihitung |
| UQ-5 | Runtime multipart 1 MB confirm (test request live ke vision) | Tidak dijalankan request upload aktual ke ocr-api | XR-01 confidence High berdasarkan default Spring Boot + absensi config, bukan probe live |
| UQ-6 | `api.render-file` legacy flow runtime behavior (bukti bahwa legacy izin-OCR masih dipanggil kondisional) | Tidak ada trace runtime; kontrak dibaca dari kode | Doc 08/09 memperlakukan W9 sebagai "kondisional, perlu keputusan (D4/D6/D10)" |
| UQ-7 | iOS build & App Store readiness | `flutter build ios` tidak dijalankan (toolchain & signing) | MOB-L01 berstatus config-only |
| UQ-8 | Jumlah/karakteristik tenant Vision selain web (bukti hanya 1 client aktif ditemukan di DB dump? tidak — dari kode & seed) | Live DB tidak diinspeksi | D3 (Vision scope SaaS) perlu konfirmasi stakeholder; klaim "1 client aktif" = dari seed/kode, bukan DB live |
| UQ-9 | Volume trafik & latensi OCR real di prod | Tidak ada metrics/logging terpusat | R-04 crash-rate & kapasitas thread tidak dihitung |
| UQ-10 | Ada tidaknya integrator eksternal yang memakai `/verify-client-token`, `OcrAPIServiceImpl`, W9 | Tidak ada referensi internal; konsumen eksternal tidak terlihat dari ketiga repo | D10 disusun sebagai pertanyaan owner |

## 4. Batasan review (transparansi)

1. **Read-only** terhadap kode aplikasi; hanya dokumen di `docs/architecture/current-state-review/` yang dibuat.
2. Semua klaim dibedakan VERIFIED / INFERRED / UNRESOLVED (tabel di atas), dan dibawa ke register 08 dengan confidence H/M/L.
3. Tidak meng-ekspose secret values — hanya nama file/key + lokasi (mis. "kredensial MySQL riil", tanpa nilai).
4. File `secrets/` (workspace root, mode 0700) tidak diinspeksi isinya sesuai mandat.
5. Deployment prod = UNKNOWN; semua diagram (02, 03) level dev dari `nest-nestara/docker-compose.yml`.
6. Review arsitektur **netral**: dokumen 09 adalah candidate decisions, bukan ADR; stack tidak dievaluasi untuk replacement kecuali bila ada bukti masalah (lihat D11 — *tidak ada bukti perlu migrasi stack Vision*).

## 5. Perintah yang tidak dapat dieksekusi (dari sandbox)

| Perintah | Alasan | Alternatif yang dilakukan |
|---|---|---|
| `mvnw test` (vision) | butuh MySQL live `127.0.0.1:3306`, tessdata, python3+ONNX native; berpotensi side-effect | baca `target/surefire-reports` historical (pass dari test body kosong — VIS-F18) |
| `flutter build ios|apk` | mutasi build + toolchain; di-scope luar review | config audit |
| `composer install/upgrade` | diharamkan (vendor dir, sandbox read-only) | `vendor/` yang ada dipakai untuk static check |
| `anvil` pipeline | tool tidak ter-install di sandbox | baca `anvil.yaml` static |
| coverage tooling (xdebug/php-cov, jacoco, flutter coverage) | tidak tersedia | coverage tidak diklaim; mobile 47/47 hanya pass rate |
| probe live ke `ocr-api:8080` | runtime tidak dijamin hidup; review read-only, tidak memicu kuota | static config + compose |

## 6. Daftar pertanyaan terbuka (digabungkan ke 09 & 08)

- **Q1 (D6/UQ-1)** — Prod akan dijalankan di mana, siapa yang mengoperasikan worker/scheduler/backup web?
- **Q2 (D4/WEB-F01/XR-04)** — Apakah W9 legacy izin-OCR masih dipertahankan? Kalau ya, model akses `/files` mana yang diterima (signed URL)?
- **Q3 (D3/UQ-8)** — Apakah Vision benar-benar SaaS multi-tenant di roadmap, atau cukup jadi internal OCR service?
- **Q4 (D5/CR-02)** — Apa retention & compliance rule (UU PDP) yang harus dipenuhi untuk KTP image + `original_extracted_text` di vision? *(pertanyaan regulasi terpisah, bukan kesimpulan review)*
- **Q5 (D10/UQ-10)** — Apakah ada konsumen eksternal untuk client-token endpoints, `OcrAPIServiceImpl` (deprecated), dan W9?
- **Q6 (D9/VIS-F14)** — Zona waktu operasional mana yang sah untuk kuota (UTC vs WIB)? — tidak ada konfigurasi existing yang menjawab.
- **Q7 (UQ-9)** — Kapasitas & beban OCR real (thread pool vision, crash native, latensi) — perlu observability sebelum set SLA.
- **Q8 (D2/XR-06)** — Format kontrak `ktp_ocr_result` yang final (list vs map di `extractedTexts`, enum status WAITING/PROCESSING/DONE/FAILURE) — siapa ownernya lintas repo?

## 7. Cross-check antar dokumen (maintainability sendiri)

| Check | Status |
|---|---|
| ID skema konsisten 01–10 | OK — XR-01..07, CR-01..02, WEB-F01..F10(+F11..F18 deferred), VIS-F01..F19, MOB-H*, M*, L*, S-01..16, R-01..14, M-01..09, D1..D11, W1..W13 |
| Setiap D* merujuk ≥ 1 temuan | OK (09) |
| Setiap temuan High merujuk bukti file:line | OK (06, 07, 08) |
| Semua diagram berbasis dev compose (bukan prod) | OK (02, 03, 05) |
| Validasi Mermaid | **Statis saja** (fence, quote-balance, ER-relasi pattern lint Python) — 10 blok mermaid di 02/03/04/05 lolos; `mmdc` tidak tersedia di sandbox (npx timeout) sehingga render visual tidak diverifikasi |
| Semua item ops prod berlabel UNKNOWN | OK — lihat §3 UQ-1 |
