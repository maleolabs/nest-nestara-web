# 08 — Architecture Risks & Technical Debt Register

Bagian dari *Current-State Architecture Review* Nestara (2026-10-19). Skala: **Critical** (potensi keamanan/data-integrity/availability serius) · **High** (risiko operasional/signifikan) · **Medium** (limitasi terkelola) · **Low** (perkembangan lokal). Setiap item punya ID stabil yang dirujuk lintas dokumen.

Urutan prioritas: cross-repository dulu, lalu per-repo. Confidence: H/M/L.

---

## 1. Cross-repository (XR)

| ID | Judul | Komponen | Severity / Conf | Deskripsi & bukti | Dampak & remediation |
|---|---|---|---|---|---|
| **XR-01** | Limit multipart tidak sinkron (1 MB vs 3 MB) | Vision↔Web/Mobile | **High / H** | `application.properties` tanpa `spring.servlet.multipart.*` → default 1 MB; validator app 3 MB (`OcrDataRequest`); web & mobile mengizinkan 3 MB | Upload KTP 1–3 MB gagal dengan 500 Spring. **Remediasi satu-baris** — set `spring.servlet.multipart.max-file-size=4MB`, `max-request-size` di properties/compose. Urgent |
| **XR-02** | do-ocr efektif sinkron 60 s vs web timeout 25 s → orphan + kuota habis tanpa hasil | Vision↔Web | **High / H** | `TextProcessorService` self-invocation (`VIS-F01`); `Impl2` hanya persist `ocr_ktp_requests` bila respons sukses; mobile menerima 502 | Mobile mengalami "layanan OCR gagal" padahal OCR bekerja; kuota vision terpakai; hasil tidak pernah diambil. Remediation: betulkan async (VIS-F01) &/atau naikkan timeout + persist-with-progress. Tie ke CR-01 |
| **XR-03** | Error-shape Vision tidak konsisten + leak pesan internal | Vision↔Web/Mobile | High / H | 404 no-body & 200-shape-vary di `OcrController:53-58,131-134,161-175`; `ErrorController.java:43-52` meleak `getLocalizedMessage()` | debugging sulit lintas-app; info-leak. Standarisasi `ApiResponse` + no stacktrace |
| **XR-04** | Legacy izin-OCR bergantung (a) simulasi-privacy `/files/{b64}` publik, (b) network reachability vision→web, (c) queue worker | Web↔Vision | High / H | `OcrServiceImpl::getUrlDokumen` (`route('api.render-file')`), `SendOcrRequest::dispatch` `PerizinalServiceImpl:73`; queue worker TIDAK ada (`WEB-F05`) | W9 saat ini efektif tidak berjalan di dev; bila W9 dikukuhkan, butuh keputusan security `/files` (D4) + topologi (D6) |
| **XR-05** | Client-token endpoints tidak dikonsumsi; implementasi punya bug | Web | Medium / H | `TokenController`, `ClientTokenService.php:139-174` (`!==` vs `hash_equals`; counter tidak naik); grep mobile = 0 | Dead surface yang menyesatkan review; kandidat hapus/refresh (D10) |
| **XR-06** | Tidak ada spec kontrak lintas-repo | Semua | Medium / H | field `ktp_ocr_result`/`KtpFieldMapper`/`extractedTexts` diduplikasi manual; tidak ada OpenAPI/typed client | Perubahan menyebar break-3; kandidat contract test / shared schema (D2) |
| **XR-07** | Vision dedupe OcrRequest by imageUrl tanpa clientId | Vision↔Web | Medium / H | `OcrRequestServiceImpl:34-44` (findByImageUrl tanpa scope) | status/hasil bisa pola-milik request client lain; ownership & kuota rusak |
| **CR-02** (cross-db) | PII retention asimetris (KTP images + teks NIK) | Vision↔Web | **High / H** | Hapus akun web menghapus file web; di Vision `original_extracted_text` & file `uploads/` tidak pernah dihapus | Data pribadi penduduk tetap di DB & disk Vision. Perlu retention policy + cleanup (D5) — detail `06-security-reliability-operations.md` |
| CR-01 | Orphan OCR ketika do-ocr timeout | Vision↔Web | High / H | do-ocr timeout → web tidak menyimpan request_id; hasil di vision tak bisa diambil (bukti: `04-data-model-and-erd.md` §5) | Sanction: persamaan Some XR-02 mitigation |

## 2. nestara-web (WEB-…)

| ID | Judul | Severity / Conf | Bukti | Rekomendasi |
|---|---|---|---|---|
| **WEB-F01** | `GET /api/files/{b64}` publik | High / H | `routes/api.php:59-61`; `FileController::render` | Credential-request signed URL ( expired token) atau cek owned; dikaitkan ke D4 |
| **WEB-F02** | `.env.testing` & APP_KEY/JWT_SECRET ter-commit | High / H | `git ls-files`; `.github/workflows/ci.yml` | Rotate + gunakan secret store; tambah gitleaks |
| **WEB-F03** | `PengaturanServiceImpl::find` salah kolom (`id` vs `user_id`) | Medium-High (data) / H | `PengaturanServiceImpl:24-30` | Fix kolom + regression test |
| **WEB-F04** | Tanpa rate-limiter signup/login/OCR API | Medium / H | tidak ada RateLimiter di `AuthController` | Tambah throttle + lockout rules |
| **WEB-F05** | Queue job tanpa worker di komposisi (W9 mati) | High (ops) / H | compose; `PerizinalServiceImpl:73` | tambah `queue:work` + supervisor; atau hilangkan W9 jika di-keputuskan (D6) |
| **WEB-F06** | Polling sinkron 2s×8s di thread HTTP (khusus `artisan serve` single-thread) | Medium / H | `OcrController:175-192` | gunakan php-fpm di compose/prod; atau async push (D6) |
| **WEB-F07** | Log raw client token; timing-compareClientToken bug | Medium / H | `TokenController:19-21`; `ClientTokenService:139-174` | hash_equals + tidak log token; hapus XR-05 |
| **WEB-F08** | Lapis env 3-lapis OCR config + `env()` di runtime (resiko config:cache) | Medium / H | `Pengaturan.php:22`, `OcrAPIServiceImpl2` | ambil dari DB saja / gunakan `config()` |
| **WEB-F09** | `cleanLivewireTmp` menghapus seluruh dir tmp (merusak concurrent upload) | Medium / H | `FileServiceImpl:71-75` | hapus file hanya yang selesai |
| **WEB-F10** | Dead code: sanctum, Imports/Perizinan, deprecated OCR impl, friendly alias | Low-Medium / H | `composer.json`, `AppServiceProvider` | sweep dead-code disiplin |
| **WEB-F11..F18** | Foto URL fallback; index/subtleties; `no_pendaftaran` race; dll | Low-Medium | report web | deferred |
|  | **`env()` di `check-env` + token render** | Low | `bootstrap/providers.php:4` dead ref | sweep |

## 3. nestara-vision-saas (VIS-…)

| ID | Judul | Severity / Conf | Bukti | Rekomendasi |
|---|---|---|---|---|
| **VIS-F01** | Async di-override self-invocation — OCR di thread HTTP | **High / H** | `TextProcessorService.java:41-56,59-92`; `OcrProcessorService.java:68-71`; `AppConfig.java:28-39` | refactor: pisah bean, `@Async public non-final`; jangan biarkan fake-WAITING |
| **VIS-F03** | keystore.jks di repo + default pwd + AES-ECB | **High / H** | root repo, `ApiKeyGenerator:25`, `KeyStoreUtil:16`, `application.properties:60-61` | External secret + AES-GCM; reissue semua key |
| **VIS-F05** | Brute force verifikasi akun | High / H | `AccountVerificationService:29-67`; repo `findByCode` | Rate-limit + bind kode ke user |
| **VIS-F08** | SSRF via `imageUrl` | High / H | `OcrDataRequest:13`; `PyTesseractOcrService:29` | egress whitelist / disable feature |
| **VIS-F07** | Uploads tanpa volume & retention; temp file leak | High (ops) / H | compose; `TesseractOcrService:305-310`; `FileManagerService:67-82` | volume mount + cleanup scheduler |
| **VIS-F09** | `process.waitFor` tanpa timeout | High / H | `PythonOcrExcecutor.java:26-56` | add timeout + watchdog |
| **VIS-F04** | NPE 500 + leak message | High / H | `OcrController:161-164`; `ErrorController:43-52` | no-op handling / 404 body |
| **VIS-F02** | multipart limit (dup XR-01) | High / H | — | s.s. XR-01 |
| **VIS-F10** | Dashboard formLogin **csrf disabled** | Medium / H | `WebSecurityConfig.java:31` | enable CSRF atau CSRF-token |
| **VIS-F11** | dedupe crossclient (dup XR-07) | Medium / H | `OcrRequestServiceImpl:34-44` | — |
| **VIS-F12** | `ExtractedTextServiceImpl::saveAll` id-reuse | Medium / H | `ExtractedTextServiceImpl:44-57` | unique row strategy |
| **VIS-F13** | kuota race | Medium / H | `RequestLimitService:22-43` | atomic UPDATE |
| **VIS-F14** | definisi-hari UTC vs lokal | Medium / H | RequestLimitService vs dashboard | pilih WIB (zona operasional) |
| **VIS-F15** | typo placeholder dash vs underscore | Medium / H | `application.properties:17,48-58` | konsisten |
| **VIS-F16** | Dead-code SaaS cluster | Medium / H | WebSocket, LLM stub, paddle, caffeine | sweep |
| VIS-F17 | README drift | Low / H | README | update docs |
| VIS-F18 | **Tests kosong** (pipeline utama) | High (kualitas) / H | test bodies empty | smoke test pipeline |
| VIS-F19 | Regenerate API key menimpa tanpa grace | Low / H | `ApiKeyServiceImpl:29-73` | dual-key transition |

## 4. nestara-mobile (MOB-…)

| ID | Judul | Severity / Conf | Bukti | Rekomendasi |
|---|---|---|---|---|
| **MOB-H01** | Profil tab menampilkan mock lokal (`UserState`) tidak sinkron server | **High / H** | `model/user.dart:8-114`, `set_profil.dart`, `profilpage.dart` | muat dari `/auth/me` + `/pemohon`; simpan sebagai cache |
| **MOB-H02 = S-06** | `usesCleartextTraffic` aktif di release | High / H | `AndroidManifest.xml:16-20` | networkSecurityConfig (debug-only) |
| **MOB-H03** | 401 mid-session tidak auto-refresh | High / H | `izin_service.dart:249-276` dsb | Dio interceptor refresh + retry |
| MOB-H04 | 3 stack state (setState/GetX/ValueNotifier+prefs) | Medium / H | navbar, homepage, set_profil | konsolidasi minimal (D7) |
| MOB-M01 | Retrofit vs Dio manual ganda + stale .g.dart fallback | Medium / M | apiclient.k, run_dev preflight | pilih satu stack |
| MOB-M06 | 11–12 deps mati + `name: b` + kode mati `asset/`,`bar_graph/` | Medium / H | pubspec, grep | cleanup sekali commit |
| MOB-M08 | config.json generator orphan | Medium / M | config.json | hapus |
| MOB-L01 | iOS belum siap (permission/ATS) | Low / H | Info.plist | tambahkan string+ATS exception |
| MOB-L05 | display nama hasil OCR berpadanan koreksi typo sederhana (`GORGNTALG`) | Low | lengkapi_profil_page.dart:451 | — |

## 5. Prioritas top-10 (rekomendasi disiplin isu ini)

1. **XR-01** — set multipart limit Vision ≥ 3 MB (fix satu baris, dampak integrasi terbesar)
2. **VIS-F01 / XR-02** — refactor async OCR (root cause failure user-facing padahal proses bekerja)
3. **WEB-F01 + XR-04** — putuskan model akses `/files` & implementasi legacy izin-OCR / hentikan W9 bila tidak dipakai
4. **CR-02 / VIS retention** — kebijakan PII (D5)
5. **VIS-F03 + WEB-F02** — secret hygiene (keystore vs commit file)
6. **VIS-F05** — rate-limit verifikasi akun vision
7. **WEB-F05** — keputusan queue worker & scheduler (D6)
8. **MOB-H01 + MOB-H03** — data mock & 401 auto-refresh (UX)
9. **VIS-F18** — minimal pipeline test (sebelum refactor VIS-F01)
10. **XR-06** — kontrak formal (OpenAPI / consumer-driven contract test) — lakukan setelah keputusan besar selesai.

Register ini disambungkan ke `09-candidate-architecture-decisions.md` (keputusan) dan `10-evidence-and-open-questions.md` (bukti).
