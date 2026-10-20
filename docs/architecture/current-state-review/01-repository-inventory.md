# 01 — Repository Inventory & Technology Stack

Bagian dari *Current-State Architecture Review* — Nestara Web / Vision / Mobile (2026-10-19).
Metodologi & batasan: lihat `../README.md`. Referensi singkat: `[WEB]` = nestara-web, `[VIS]` = nestara-vision-saas, `[MOB]` = nestara-mobile.

---

## 1. Verifikasi path & struktur repositori

Path yang diasumsikan dalam brief saat ini memakai `../vision` dan `../mobile`; nama aktual **berbeda** dan telah diverifikasi:

| Asumsi | Path aktual (terverifikasi) |
|---|---|
| `.` — Nestara Web | `nest-nestara/nestara-web` (git repo) |
| `../vision` | `nest-nestara/nestara-vision-saas` (git repo) |
| `../mobile` | `nest-nestara/nestara-mobile` (git repo) |

Selain tiga repo, direktori parent `nest-nestara/` berisi **deployment system-level dan artefak pendukung** (semuanya terverifikasi lewat listing 2026-10-19):

| Item | Isi | Relevansi |
|---|---|---|
| `docker-compose.yml` | Compose workspace dev: `nestara-db` (MySQL 8, db `nestara_dev`, port 3306), `ocr-db` (MySQL 8, db `ocr_tool`, port 3307), `ocr-api` (Vision, port 8080), `web` (php:8.4-cli + composer install + `artisan serve`, port 8000). Web→Vision via Docker DNS `http://ocr-api:8080/api/`. | Bukti deployment topology **level dev**, bagian `02-current-system-architecture.md`. |
| `.env.example`, `docker-compose.override.yml.example` | Override password opsional. | Konfigurasi. |
| `KTP/` | Dua file `.jpg` (mode 0600, ±317 KB & ±470 KB) — sampel/fixture gambar KTP di root workspace. | Risiko data pribadi di luar repositori (`06-security-reliability-operations.md` §S.6). |
| `secrets/` | Direktori berijin 0700. Tidak diinspeksi isinya sesuai mandat. | — |
| `tool/` | `check-env.sh`, `check-env.ps1` (deteksi port + curl health `/up`). | Ops. |
| `README.md` | Panduan dev 3-app (run web native vs Docker, vision, mobile via `tool/run_dev.dart`). | Akurat terhadap kode; lihat dokumentasi Vision di bawah untuk overclaim. |

### Struktur top-level per repo

**nestara-web** (Laravel): `app/` (Controllers + Livewire + Services + Models + Jobs), `bootstrap/app.php`, `config/`, `database/migrations` (22 file), `routes/{web,api,console}.php`, `resources/views`, `storage/app/private_files` (dokumen terenkripsi), `docs/` (workspace-README + diagrams PlantUML), `tests/`, `vendor/`, `node_modules/` (generated — bukan source), `.anvil/` + `anvil.yaml` (standard internal MaleoLabs), `CHANGELOG.md` (masih skeleton Laravel — inconclusive), `VERSION` + `app/Support/Version`.

**nestara-vision-saas** (Spring Boot/Maven): `src/main/java/m2codes/ocr_tool/{application,domain,infrastructure,interfaces}` (114 file Java; layered), `src/main/resources` (properties, Thymeleaf templates, static assets), `opt/app/ocr/*.py` (pipeline Python: `tesseract_ocr.py`, `rapid_ocr.py`, `paddle_ocr.py`), `scripts/`, `Dockerfile`, `docker-compose.yml` + `docker-compose.workspace.yml`, `keystore.jks` (di-commit), 3 file `hs_err_pid*.log` (JVM crash log historis), `target/` + `mvnw`, `docs/` + `README.md`, `uploads/` (akumulasi file OCR).

**nestara-mobile** (Flutter): `lib/` (57 file: `client/`, `model/`, `page/`, `widget/`, `storage/`, `flavor/`, `theme/`, `navbar/`, + **direktori dead code** `asset/`, `bar_graph/`), `tool/run_dev.dart` (launcher dev), `scripts/`, `test/` (10 file, 47 test), `android/`, `ios/`, `build/` (generated), `.anvil/` + `anvil.yaml`, `config.json` (konfigurasi generator Anvil yang tidak aktif — `MOB-M08`).

---

## 2. Tabel perbandingan technology stack

| Aspek | nestara-web | nestara-vision-saas | nestara-mobile |
|---|---|---|---|
| Bahasa/runtime | PHP ^8.4 | Java 17 | Dart SDK ^3.5 (Flutter 3.38.5/Flutter SDK di mesin review) |
| Framework | Laravel 13 (`composer.json`) | Spring Boot 3.3.3 (`pom.xml:13-16`) | Flutter (Material 3) |
| UI | Blade + Livewire 3.8; **3 stack CSS bersamaan** (Bootstrap 5.3, Tailwind v4, Material Web 1.5) + pdfjs-dist, axios (`package.json`) | Thymeleaf + Chart.js (CDN) + `tailwind.config.js` | Widget + Google Fonts (plusJakartaSans), theme token port dari web |
| API layer | Routing `routes/api.php` + controllers `App\Http\Controllers\Api\*` + Resources | Spring MVC controllers (`interfaces/controller/OcrController` dsb.) | Retrofit 4.2 (hanya `client/apiclient.dart`, 3 endpoint auth) + **Dio manual** untuk 4 service lain |
| Auth | tymon/jwt-auth 2.3 (API, JWT TTL 30 hr), laravel/ui session (web), spatie/laravel-permission 6.9 (role); Sanctum 4.3 **tidak dipakai** (`WEB-F10`) | Spring Security `SecurityFilterChain` ×2 (API stateless via `ApiKeyAuthFilter`; dashboard formLogin session, timeout 1500m) | JWT Bearer di `flutter_secure_storage`; **client-token endpoints web tidak dipakai** (`XR-05`) |
| Database | MySQL 8 (dev via compose; `.env.example` default sqlite) — Eloquent, 22 migrations | MySQL 8, Spring Data JPA, **`ddl-auto=update` tanpa migration tool** | Tidak ada DB server — `flutter_secure_storage`, SharedPreferences, file manifest (`RuntimeStore`) |
| OCR/processing | Client OCR: `OcrAPIServiceImpl2` (Http facade multipart, timeout 25 s) | Tess4J 5.12 + OpenCV 4.5.1 (native JVM) + subprocess Python (pytesseract, RapidOCR-onnxruntime, PaddleOCR legacy) + LLM-vision stub | `image_picker`/`file_picker` (capture & upload saja) |
| Job/queue | Queue database (`QUEUE_CONNECTION=database`) + job `SendOcrRequest`; **tidak ada worker di komposisi** (`WEB-F05`) | `@Async` executor (`PHYSICAL_DEVICE_*` pool 3/5/50) — **bypass oleh self-invocation** (`VIS-F01`) | — |
| CLI/build | Composer, Vite 5, Pint (formatter), artisan | Maven wrapper, Docker | Flutter pub, build_runner (retrofit saja), Anvil pipeline |
| Testing | PHPUnit 12 (`tests/Feature`, `tests/Unit`) | `spring-boot-starter-test` — **test body kosong untuk pipeline utama** (`VIS-F18`) | `flutter_test` 47 test hijau (ereksekusi mandiri) |
| CI | GitHub Actions (ci.yml — mysql service, pint) | qodana.yaml (tanpa workflow run terverifikasi) | GitHub Actions: ci (analyze+test), build, release (Flutter pinned) |
| Package manager/artefak | composer.lock, package-lock.json | pom.xml, Maven repo (tidak ada lockfile) | pubspec.lock |

**Catatan dependency-declared vs used:**

- Web: `laravel/sanctum` ter-deklarasi, hanya `config/sanctum.php` + tabel `personal_access_tokens` untuk hygine hapus akun (`app/Http/Controllers/Api/UserController.php:105-110`). `maatwebsite/excel` + `app/Imports/Perizinan/*` tidak di-panggil dari route (UNRESOLVED #5 README).
- Vision: `spring-boot-starter-cache` + Caffeine ter-deklarasi, **zero `@Cacheable`**; `websocket` starter ter-deklarasi, endpoint `/ocr-ws` **dead code** (`VIS-F16`); `app.api-key.length=32` dikonfigurasi, tidak pernah dipakai.
- Mobile: 12 dependency tanpa satu pun import (grep verifikasi): `provider`, `rxdart`, `obs`, `syncfusion_flutter_charts`, `flutter_charts`, `bottom_bar_matu`, `animated_theme_switcher`, `iconly`, `line_icons`, `cupertino_icons`, `lib`, `json_annotation` (`MOB-M06`).

---

## 3. Entry points & startup

| Repo | Entry point | Mekanisme startup | Health |
|---|---|---|---|
| Web | `public/index.php:17-20` (`handleRequest`), `artisan` | `php artisan serve` (dev/CLI) atau container compose `php 8.4-cli` (`docker-compose.yml:100-127`: composer install → migrate --force → artisan serve) | `GET /up` (`bootstrap/app.php:21`) + `check-env.sh` |
| Vision | `OcrToolApplication.java` (Spring Boot main) | `mvnw`, jar, atau Docker (`Dockerfile`, compose) | Tidak ada health endpoint khusus (endpoint `/` WelcomeController) |
| Mobile | `lib/main.dart:9-11` → splash → session gate (`resolveSession`, `auth_service.dart:172-223`) | `flutter run` via `tool/run_dev.dart` (`--dart-define=API_BASE_URL`) | Preflight health `/up` web (run_dev.dart:452-470) |

## 4. Build, test, deployment tooling

- **Anvil** (standard internal MaleoLabs) ada di ketiga repo (`anvil.yaml`): definisi proyek, varian rilis mobile (`alpha`/`beta`/`stable`), pipeline CI. `anvil.yaml` mobile: beta/stable masih **placeholder domain `.example.com`**.
- **Web CI**: GitHub Actions `ci.yml` (MySQL service, composer, pint). `.env.testing` ter-track di git (`git ls-files`) dan memuat kredensial riil — `WEB-F02`.
- **Vision**: build Docker (Dockerfile base Java 17 + apt tesseract/python + pip rapidocr). `mvnw test` tidak dijalankan ulang saat review (butuh MySQL live + native deps), historical surefire shows pass dari stub kosong.
- **Mobile**: `flutter test` → **47/47 passed**, `flutter analyze` → **no issues** (dieksekusi 2026-10-19, working tree tetap bersih).
- **Web test eksplorasi**: `php artisan test` → 37 passed / 17 failed; kegagalan didominasi oleh kredensial `.env.testing` (MySQL tidak tersedia di sandbox), 1 failure logis (`KtpFieldMapperTest` kapitalisasi 'Jawa Timur'). Laporan lengkap: `07-testing-and-maintainability.md`.

## 5. Dokumentasi yang ada

| Repo | Dokumen | Status terhadap kode |
|---|---|---|
| Web | `docs/workspace-README.md` (run dev 3-app, split-brain OCR config), `docs/diagrams/src/database.puml`, `README.md`, `CHANGELOG.md` | Komentar split-brain konsisten; `CHANGELOG` skeleton; diagram puml belum di-cross-check penuh |
| Vision | `README.md`, `docs/ocr-improvements-2026-08-21.md`, `qodana.yaml` | **Drift**: README klaim ensemble "15 PSM passes" (aktual 4), conf fallback 60 (aktual 55), WebSocket progress aktif (dead code), "Data tidak dipindahkan" (uploads akumulasi permanen) — `VIS-F17` |
| Mobile | `README.md` (1 paragraf), tooling `run_dev.dart` | Minim |
| Workspace | `nest-nestara/README.md` | Akurat (run dev, split-brain, docker mode) |

## 6. Rekomendasi konteks inventory

- Semua stack modern dan dalam versi yang relatu baru kecuali **tess4j/OpenCV native** (permukaan crash native terdokumentasi, `VIS-F06`) dan **deps mobile 12 tidak dipakai**.
- Tidak ada dependency-locking untuk Vision (Maven) — reproducibility lemah.
- `config.json` mobile menunjuk generator kode yang tidak eksis → perlu dihapus atau diaktifkan (`MOB-M08`).

Cakupan inventory ini dibatasi oleh ketidaktersediaan runtime produksi (lihat README §Batasan).
