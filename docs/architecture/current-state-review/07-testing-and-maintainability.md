# 07 — Testing & Maintainability

Bagian dari *Current-State Architecture Review* Nestara (2026-10-19). Semua perintah yang dijalankan bersifat read-only; tidak meng-install/upgrade apa pun.

---

## 1. Pemeriksaan yang benar-benar dijalankan (2026-10-19)

| Repo | Perintah | Hasil | Catatan mutasi? |
|---|---|---|---|
| mobile | `flutter test` | **47/47 PASSED** | tidak; `git status` tetap bersih |
| mobile | `flutter analyze` | **No issues found** | tidak |
| web | `php artisan test` (`vendor/bin/phpunit`) | **37 passed / 17 failed** (128 assertions) — 16 failure berasal dari kredensial `.env.testing` (MySQL tidak tersedia di sandbox), **1 failure logis**: `KtpFieldMapperTest` case kapitalisasi `JAWA TIMUR` vs `Jawa Timur` | tidak menulis kode; `.phpunit.result.cache` sudah ada |
| web | `vendor/bin/pint --test` | **223 file pending formatter** | tidak |
| web | `php -r class_exists('App\Providers\EventServiceProvider')` | false (dead reference di `bootstrap/providers.php:4`) | tidak |
| web | `git ls-files .env.testing` | ter-track (bukti `WEB-F02`) | tidak |
| vision | `mvnw test` | **TIDAK dijalankan** — butuh MySQL live di 127.0.0.1:3306, tessdata & python3 native; risiko side-effect. Historical surefire (target/) memperlihatkan pass, tapi dari **test body kosong** (`VIS-F18`) | skipped |
| semua | grep-based dependency audit | mobile: 12 deps tanpa 1 import; web: sanctum/excel dead; vision: cache/ws/paddle/LLM dead | — |

Test web yang dianggap gagal membutuhkan **MySQL service CI** (`.github/workflows/ci.yml` memuat service mysql) — berarti CI harus menyediakan DB `perizinan_ptsp`; pada sandbox tidak ada; jadi **gagal env, bukan gagal kode** (kecuali 1 case logis KtpFieldMapperTest).

## 2. Inventori test per repo

### 2.1 Mobile — dari paling sehat
- 10 file test, 47 test: `auth_gate_test` (regresi auto-login + Bearer-prefix), `token_manager_test` (migrasi legacy prefs → secure storage), `ocr_service_stage_test` (stage polling dan error burst), `runtime_store_test` (File IO), model tests (izin, dokumen, ktp_ocr_result), `user_state_test`, widget smoke.
- Yang **belum** dites: alur wizard step-2 validasi file picker, integrasi penuh network (tidak ada integration test), API layer (semua Dio manual — testnya tinggi coupling), `Get.find` tab-switch pointer (page-level).

### 2.2 Web — layered tapi config-fragile
- Feature test: `tests/Feature/{Api/*, PemohonStoreTest, DeleteAccountTest}`, `Feature/APIClient/{Emsifa,Ocr}`, `Feature/AppService/FileService`, `Feature/DomainService/{Entri,Ocr,Pemohon,Perizinan,Role}`, `Feature/Security/TokenService`.
- Unit test: `Requests/OcrKtp`, `Services/KtpFieldMapper`, `Support/Version` (subsystem VERSION anvil).
- **Gap penting yang belum punya test**: `/api/files` (endpoint publik), OcrController polling flow, alur wizard Livewire, race `no_pendaftaran`, delete izin transaksional, konfigurasi OCR berbasis DB di Pengaturan.

### 2.3 Vision — hampir tanpa test
- `src/test/java/m2codes/ocr_tool`: **`OcrProcessorServiceTest.testProcessOcr_Success()` kosong**, `TesseractOcrServiceTest.extractTextFromImageTest()` kosong; hanya `contextLoads` yang meaningful. Tidak ada tes untuk ApiKeyAuthFilter, kuota, pipeline ensemble.
- Motor quality: qodana.yaml (Statically configured, tidak wajib-run), tidak ada CI Java.

## 3. Static analysis & quality gates

| Repo | Tool | Hasil |
|---|---|---|
| web | **Laravel Pint** formatter | 223 file pending — style debt besar (masih bisa autofix, tapi perlu baseline) |
| web | PHPStan/Psalm | tidak di-install |
| mobile | `flutter analyze` | clean |
| mobile | flutter_lints ^4 (3 rules dynamic off) | Low — linter version belum naik-kan, `analysis_options.yaml:11-15` |
| vision | qodana | file ada; tidak tereksekusi |

## 4. Maintainability keys

### 4.1 Duplikasi / coupling (verified)

| ID | Temuan | Bukti |
|---|---|---|
| M-01 | **6 error-mapper manual di mobile** dengan pola identik (~80% kemiripan kode) | `izin_service.dart:249-276`, `pemohon_service.dart:65-81`, `ocr_service.dart:151-176`, `auth_service.dart:409-439` |
| M-02 | **2 stack API mobile**: Retrofit (3 endpoint auth) vs Dio manual (sisanya) | `apiclient.dart` + 4 service |
| M-03 | **Kode wizard izin diduplikasi web vs API**: `CreateIzin::save` meniru `PerizinalServiceImpl.saveRegistrationWithPersyaratan` tapi beda semantik error | `CreateIzin.php:76-83` |
| M-04 | **Presentation logic di backend controller** — `Admin\JenisPerizinanController::loadDataPerizinan` membangun markup HTML | `admin/JenisPerizinanController.php:25-60` |
| M-05 | **3 stack CSS web (Bootstrap + Tailwind v4 + Material) + pdfjs + axios dalam satu app** | `package.json` |
| M-06 | **Mobile: 12 dependency mati + pubspec `name: b`** + dead-code lib (`asset/` dan `bar_graph/`) | `pubspec.yaml`, grep |
| M-07 | **Vision dead-code cluster**: cache+caffeine tanpa pakai, WebSocket `/ocr-ws` dead, LLM stub, Paddle (disabled), `ExternalFileUploader` stub, `ResearchTitleExtractor` | `AppConfig.java`, `WebSocketConfig.java`, `LlmVisionExtractorService.java`, dsb. |
| M-08 | `config.json` mobile menerus Anvil generator yang tidak ada — config orphan | `config.json:1-12` |
| M-09 | **Controller admin web inisiator HTML** dan **2 stack OCR web scheduler** (`OcrAPIServiceImpl` + Impl2) yang sama-sama masih ada, satu deprecated | `OcrAPIServiceImpl.php` vs `OcrAPIServiceImpl2.php`, `AppServiceProvider:102` |

### 4.2 Dokumentasi drift

| Repo | Temuan |
|---|---|
| vision README | klaim 15 PSM passes vs kode 4; conf threshold 60 vs 55; WebSocket progress aktif (dead); "Data tidak dipindahkan" vs upload ber-array permanen `uploads/` |
| web CHANGELOG.md | masih skeleton Laravel 11 |
| mobile README | 1-paragraph, tidak berubah — sesuai keterbatasan repo |

## 5. Komando yang tidak dapat dieksekusi

| Perintah | Alasan |
|---|---|
| `mvnw test` (vision) | butuh MySQL live di `127.0.0.1:3306`, tessdata, dan python3 + paket ONNX — berpotensi mutasi; diputuskan skip, evidence dari `target/surefire-reports` histori |
| `flutter build *` | dianggap mutasi build (P), di-scope review |
| `composer install/upgrade` | diharamkan ( Vendor dapak di-id) |
| `anvil pipeline` | tool tidak ter-install di sandbox |
| coverage measure (xdebug/phpcoverage/coverage tooling) | tidak tersedia; **mobile coverage tidak diklaim** — 47/47 hanya pass rate |

## 6. Rekomendasi quality-gates

1. Buka rate mobile: CI sudah test+analyze — cukup solid; tambahkan **integration-test** untuk `OcrService` polling & `RuntimeStore`.
2. Web: fix `.env.testing` (rotate + move to secrets), masukkan `RateLimiter` untuk login api, sederhanakan Pint (autofix satu commit), tambah test `/api/files` ownership 403/401.
3. Vision: buat minimal smoke test pipeline (`EnsembleOcrService` sample image), matikan/ambil-ljm hst drift, tetapkan instruksi run_test python native.
