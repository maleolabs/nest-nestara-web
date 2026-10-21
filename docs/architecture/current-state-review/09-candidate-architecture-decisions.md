# 09 — Candidate Architecture Decisions

Bagian dari *Current-State Architecture Review* Nestara (2026-10-19). **Ini bukan ADR dan bukan keputusan yang disahkan** — daftar keputusan yang perlu dibahas oleh tim, berdasarkan bukti dari review. Format: pertanyaan → keadaan kini → alternative & tradeoff → konsekuensi jika dibiarkan → rekomendasi & confidence.

---

## D1 — Model eksekusi OCR (sinkron vs asinkron nyata)

- **Keadaan kini**: pipe OCR dirancang asinkron (`@Async`, WAITING status, Slack thread pool) tetapi **async di-bypass** oleh self-invocation (`VIS-F01`) — do-ocr sebenarnya sinkron sampai 60 s; web timeout 25 s (`XR-02`); mobile menanggung wait 120 s.
- **Pilihan**: (a) perbaiki async → do-ocr cepat return `requestId`; (b) document-kan sync contract sesungguhnya → web naikkan timeout ke 70–90 s (membutuhkan php-fpm / concurrency planning); (c) polling-first API (do-ocr langsung `requestId`, tanpa payload OCR result).
- **Tradeoff**: (a) mengikuti desain yang sudah tertulis; (b) sederhana tapi rawan thread-exhaustion.
- **Bila dibiarkan**: upload KTP makin sering gagal user-facing; orphan & kuota menumpuk.
- **Rekomendasi**: (a) + hambatan di polling. Confidence **High**.

## D2 — Governance kontrak API antar repositori

- **Keadaan kini**: kontrak dipelihara "berbarengan manual" di 3 repos (XR-06); mobile `_extractList` menangani 3 bentuk response; web `KtpFieldMapper` men-dependent `extractedTexts` list/map.
- **Pilihan**: (a) tulis OpenAPI spec + generate client; (b) consumer-driven contract test (masing-masing repo punya modifier deps); (c) biarkan + tulis dokumen kontrak word-of-mouth.
- **Tradeoff**: (a) unuk tiga app PHP/Java/Dart — perlu codegen di masing-masing beban build; (b) murah dan menjaga integrasi; (c) tidak menekan drift.
- **Rekomendasi**: mulai dari **(b) contract test** di CI web + vision (yang paling mudah) lalu (a). Confidence **Medium-High**.

## D3 — Ownership & scope Vision sebagai SaaS

- **Keadaan kini**: Vision memenuhi fitur SaaS penuh (users/clients/api key/kuota/dashboard/playground), tapi **realita hanya 1 client aktif (web) + playground**; role ADMIN dead; sekadar 1 DB.
- **Pertanyaan**: mempertahankan SaaS multi-tenant (dan investasi maintenance-nya) atau menyederhanakan ke "internal OCR service" (tetap sebagai service, hapus fitur tenant/kota).
- **Kompleksitas**: signable trade-off: fitur SaaS yang tidak terpakai menambah attack surface & configurability (keystore, key rotation, kuota, verifikasi) (S-02/S-04/D5).
- **Rekomendasi**: keputusan produk; kalau SaaS tidak dalam roadmap nyata, sederhanakan internal service terbesar dulu. Confidence **Medium** (butuh stakeholder).

## D4 — Model akses `/files/{b64}` (publik-hari-nama vs signed URL)

- **Keadaan kini**: `/api/files/{base64}` publik (`WEB-F01`) dipakai mobile RuntimeStore & legacy izin-OCR vision (XR-04) — endpoint disengaja namun satu-satunya lindung = obfuscation base64.
- **Pilihan**: (a) tambahkan JWT auth + ownership check pada endpoint; (b) signed/short-lived URL (token HMAC kedalam URL); (c) biarkan.
- **Tradeoff**: signed URL paling kecil menghambat mobile (RuntimeStore cukup add token di URL) dan memungkinkan vision fetch via URL dengan token temporer.
- **Rekomendasi**: **(b)** signed URL dengan TTL. Confidence **Medium-High**.

## D5 — Retensi PII & kepatuhan (UU/dkk.)

- **Keadaan kini**: file KTP (mobile flow) dihapus di web oleh OcrController ketika terminal; **vision tetap menyimpan** `original_extracted_text` (NIK/nama) tanpa batas + file `uploads/ocr-request` tanpa volume & retention (`VIS-F07`, CR-02); `KTP/` fixtures di workspace root; `.env.testing` berisi kredensial.
- **Pilihann**: retention policy (hapus row+file di vision setelah N hari atau on-policy), masking hasil, siklus data cleanup war; juga setuju retention di samsa storage (web & vision).
- **Rekomendasi**: define retention ≤ X hari untuk vision (mis. 24–48 jam), implement cleanup scheduler. Confidence High (wajib regulasi tetap diriset terpisah).

## D6 — Deployment topology & runtime ops (prod)

- **Keadaan kini**: tidak ada artefak prod; dev = compose + `artisan serve` single-thread + **queue worker tidak hidup** (`WEB-F05`); scheduler tidak ada; backup tidak ada; vision `ddl-auto=update`.
- **Pertanyaan**: prod akan jalan di mana, siapa mengoperasikan worker/scheduler/backup, bagaimana migration vision diguanakan (Flyway?).
- **Rekomendasi**: decide topologi sebelum beta-coder: `php-fpm + nginx` (bukan `artisan serve`), tambahkan `queue:work` + supervisor (jika W9 dipertahankan), scheduler di-level prod, backup DB scheduled (mysqldump cron / managed DB), dan pilih Flyway untuk vision. Confidence High (per confirmation prod).

## D7 — Konsolidasi arsitektur mobile

- **Keadaan**: 3 paradigma state + 2 stack API + UserState mock (`MOB-H01`, `MOB-H04`, M-02); pubspec `name: b`; 12 deps mati.
- **Pilihan**: minimal-invasive consolidations vs restructure (GoRouter/repository/clean).
- **Rekomendasi**: minimal dulu — muat profil dari server (H01), tambahkan interceptor refresh (H03), hapus dead code/deps, rename package. Restructure besar (router/manager) hanya bila fitur mobile bertambah besar. Confidence High.

## D8 — Secret & key management lintas sistem

- **Keadaan**: keystore.jks Vision di-commit (`S-02`), AES-ECB; `.env.testing` web ter-commit (`S-03`); keystore password default; API key Vision disimpan di DB web tanpa rotation; mobile anvil prod domain masih placeholder.
- **Pilihan**: env-based secret manager / CI secrets / vault.
- **Rekomendasi**: rotate semua secret; keystore via CI secret, bukan repo; tambahkan tooling `gitleaks` di CI; kelola rotasi API key dengan dual-key window (`VIS-F19`). Confidence High.

## D9 — Definisi "hari" kuota Vision & kuota besaran

- **Keadaan**: kuota harian dihitung UTC (`RequestLimitService`) sedangkan chart dashboard pakai systemDefault + `CONVERT_TZ (+07:00)` — definisi hari tidak konsisten.
- **Rekomendasi**: normalisasi ke satu zona (WIB). Confidence High.

## D10 — Kontrak versi API & deprekasi endpoint legacy (client-token, `OcrAPIServiceImpl`, W9)

- **Keadaan**: `/verify-client-token` & `/generate-client-token` tidak dipakai oleh 3-app dengan bug internal (XR-05); `OcrAPIServiceImpl` deprecated; W9 legacy terpakai kondisional.
- **Pertanyaan**: apakah perlu dihapus atau ada integrator eksternal (pengiriman web-token manual / QR) yang belum terlihat?
- **Rekomendasi**: konfirmasi dengan owner; bila tidak ada konsumen eksternal → depresiasi dasar (ebilasi bug). Confidence Medium.

## D11 — Bahasa/stack Vision tetap Java/Spring?

- **Keadaan**: review berasas "do-not-prescribe" — Vision Java 17/Spring Boot berjalan baik, dengan risiko **native crash** yang terdokumentasi (hs_err logs) dan pipeline Python yang memisah runtime.
- **Pertanyaan**: perlu evaluasi jika stack vision direncanakan migrasi (misal Python-only pipeline atau Go service)?
- **Rekomendasi**: **tidak ada bukti perlu migrasi**; risiko native tartto-manageable dengan guardedExtract + fallback chain; metrik utama = keandalan & operasional, bukan bahasa. Confidence Medium.

## Ringkasan urutan rekomendasi

Keputusan dengan efes greatest-impact & d-lowest effort: **D1 (async OCR), XR-01 (multipart), D4 (signed URL), D8 (secret)**. Keputusan yang membutuhkan input stakeholder: D3 (Vision scope), D5 (PII/retensi), D6 (topologi prod).
