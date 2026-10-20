# Current-State Architecture Review — Nestara Web / Vision / Mobile

Review arsitektur lintas-repo, **evidence-backed**, disusun 2026-10-19. Cakupan: tiga aplikasi Nestara plus artefak deployment level workspace. Review ini **read-only** terhadap kode aplikasi — hanya dokumen di direktori ini yang dibuat. Arsitektur dinilai netral: dokumen 09 berisi *candidate decisions*, bukan ADR dan bukan keputusan yang disahkan.

---

## 1. Repositori yang diinspeksi

| Repo | Path | Stack |
|---|---|---|
| nestara-web (repo utama review) | `nest-nestara/nestara-web` | PHP ^8.4, Laravel 13, Livewire 3.8, MySQL 8 |
| nestara-vision-saas | `nest-nestara/nestara-vision-saas` | Java 17, Spring Boot 3.3.3, Tess4J + OpenCV + Python pipeline, MySQL 8 |
| nestara-mobile | `nest-nestara/nestara-mobile` | Flutter (Dart SDK ^3.5), Material 3 |
| Workspace parents | `nest-nestara/docker-compose.yml`, `README.md`, `tool/`, `KTP/`, `secrets/` (tidak diinspeksi isinya) | Docker Compose dev topology |

Nama path diasumsikan `../vision`/`../mobile` di brief awal — **terverifikasi berbeda**; detail di `01-repository-inventory.md` §1.

## 2. Metodologi & batasan

- Bukti wajib file + line + symbol. Tiga level klaim: **VERIFIED** (kode dibaca / perintah dieksekusi), **INFERRED** (logis dari bukti, belum diuji runtime), **UNRESOLVED** (butuh runtime prod atau jawaban owner).
- Deployment produksi = **UNKNOWN** (tidak ada artefak prod); semua diagram level dev dari docker-compose.
- Tidak meng-ekspose secret values; `secrets/` tidak diinspeksi.
- Detail batasan + daftar lengkap unknowns: `10-evidence-and-open-questions.md`.

## 3. Indeks dokumen

| # | Dokumen | Isi |
|---|---|---|
| 01 | `01-repository-inventory.md` | Verifikasi path, tech stack perbandingan, entry point, build/test tooling, dokumentasi drift |
| 02 | `02-current-system-architecture.md` | System context diagram, container/deployment diagram (dev), internal architecture per app, ownership matrix |
| 03 | `03-api-and-integration.md` | Inventori API web & vision, pemetaan kontrak lintas-repo, mismatch, matriks timeout/polling, sequence diagram, trust boundary |
| 04 | `04-data-model-and-erd.md` | ERD DB web & vision (Mermaid), mapping entitas lintas-sistem, risiko konsistensi cross-DB |
| 05 | `05-end-to-end-workflows.md` | 13 workflow (W1–W13), journey OCR KTP↔izin, lifecycle status, failure handling, gap |
| 06 | `06-security-reliability-operations.md` | Auth boundary, temuan security S-01..S-16, reliability R-01..R-14, config precedence, migrasi, deployment ops |
| 07 | `07-testing-and-maintainability.md` | Hasil test yang benar-benar dijalankan, inventori test per repo, quality gates, maintainability M-01..M-09 |
| 08 | `08-architecture-risks-and-technical-debt.md` | Register risiko prioritas: cross-repo XR-01..XR-07 + CR, per-repo WEB-F, VIS-F, MOB-*, top-10 prioritas |
| 09 | `09-candidate-architecture-decisions.md` | 11 keputusan kandidat (D1–D11): pertanyaan → keadaan kini → alternatif → konsekuensi → rekomendasi + confidence |
| 10 | `10-evidence-and-open-questions.md` | Register bukti per klaim, daftar unknowns (UQ-1..UQ-10), perintah yang tidak dieksekusi, pertanyaan terbuka Q1–Q8 |

## 4. Skema ID stabil

| Prefix | Domain | Dipakai di |
|---|---|---|
| `XR-01..07` | Risiko lintas-repo | 08 (register utama) |
| `CR-01..02` | Konsistensi data cross-DB | 04, 08 |
| `WEB-F*` | Temuan web | 03, 06, 08 |
| `VIS-F*` | Temuan vision | 03, 06, 08 |
| `MOB-H/M/L*` | Temuan mobile (High/Medium/Low) | 08 |
| `S-01..16`, `R-01..14`, `M-01..09` | Security / Reliability / Maintainability | 06, 07 (alias ke F-ID) |
| `D1..D11` | Keputusan kandidat | 09 |
| `W1..W13` | Workflow domain | 05 |
| `UQ-1..10`, `Q1..Q8` | Unknowns & pertanyaan terbuka | 10 |

## 5. Ringkasan eksekutif

Nestara terdiri dari satu monolith Laravel (web + API + Livewire back-office), satu layanan OCR berlayer Spring Boot yang diposisikan sebagai SaaS namun pada realitasnya hanya melayani satu client aktif (web) dan playground, serta satu app mobile Flutter sebagai channel pemohon. Topologi dev hidup di satu compose: web:8000 → vision `ocr-api:8080`, dua MySQL (3306/3307).

Kualitas di atas rata-rata yang sudah ada: enkripsi file at-rest + ownership check + delete-account hygiene di web; `guardedExtract` per-engine OCR di vision; test mobile hijau (47/47) dan `flutter analyze` bersih.

Tapi fondasi integrasinya rapuh pada empat sudut yang sama-sama High dan saling memperparah:

1. **Kontrak multipart tidak sinkron (XR-01)** — Vision default 1 MB vs kontrak 3 MB; foto KTP 1–3 MB gagal 500. Fix satu baris.
2. **Async OCR di-bypass (VIS-F01/XR-02)** — `do-ocr` efektif sinkron 60 s di thread HTTP, sementara web timeout 25 s → response gagal padahal OCR sukses; hasil orphan (CR-01), kuota vision terpakai sia-sia, mobile menampilkan "layanan OCR gagal".
3. **Endpoint publik berisi PII-indirect (`/api/files/{b64}`, WEB-F01/XR-04)** — satu-satunya proteksi = obfuscation base64; dipakai mobile dan legacy izin-OCR vision.
4. **Retensi PII asimetris (CR-02)** — web menghapus file KTP saat terminal; vision menyimpan `original_extracted_text` (NIK/nama) + file upload tanpa batas, tanpa volume mount.

Di atas itu: keystore.jks ter-commit (VIS-F03), `.env.testing` berisi kredensial riil ter-track git (WEB-F02), queue worker tidak ada di komposisi sehingga legacy izin-OCR (W9) efektif tidak berjalan (WEB-F05/XR-04), dan tidak ada spec kontrak lintas-repo (XR-06) sehingga 3 bentuk response ditangani manual per aplikasi. Mobile utamanya debt UX: profil dari mock lokal (MOB-H01) dan 401 tidak auto-refresh (MOB-H03).

Urutan eksekusi rekomendasi tertinggi ROI: **XR-01 → VIS-F01/XR-02 → D4 (signed URL) → D8 (secret hygiene) → D5 (retensi PII)**; sisanya butuh input stakeholder (D3, D6, D10).

**Completeness:** review penuh terhadap source code ketiga repo + dev compose; **parsial** untuk runtime behavior (prod topology, live DB state, reachability vision→web, multipart live confirm, iOS build) — semua berstatus UNKNOWN dan tercantum di `10` §3.

## 6. Fakta vs inferensi — disiplin

Setiap temuan di dokumen 08 memakai format `Severity / Confidence` (H/M/L). Data yang diklaim:
- **VERIFIED**: pembacaan file/line/symbol; output perintah yang tercatat di `07` §1 — lihat register `10` §2.
- **INFERRED**: konsekuensi runtime berbasis kode-path (contoh: timing OCR 25–60 s, efek multipart 1 MB).
- **UNRESOLVED**: terdaftar eksplisit di `10` §3 (UQ-1..UQ-10) — jangan mengutip review ini sebagai fakta untuk item tersebut.
