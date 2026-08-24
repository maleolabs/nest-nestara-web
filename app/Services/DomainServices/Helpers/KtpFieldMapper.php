<?php

namespace App\Services\DomainServices\Helpers;

/**
 * Memetakan hasil OCR KTP dari vision service menjadi struktur field
 * yang aman dikirim ke client.
 *
 * Sumber nama field: nestara-vision-saas
 * (opt/app/ocr/extractor/ktp_extractor.py -> EXPECTED_KEYS).
 * Output dibatasi whitelist sehingga kredensial (api key / base url)
 * tidak pernah ikut dalam hasil pemetaan.
 */
class KtpFieldMapper
{
    /**
     * Keys hasil ekstraksi KTP yang dikenali vision service.
     */
    public const KTP_KEYS = [
        'provinsi', 'kabupaten', 'kota', 'nik', 'nama', 'tempat/tgl lahir',
        'jenis kelamin', 'gol. darah', 'alamat', 'rt/rw', 'kelurahan',
        'kecamatan', 'agama', 'status perkawinan', 'pekerjaan',
        'kewarganegaraan', 'berlaku hingga'
    ];

    public const STATUS_DONE = 'done';
    public const STATUS_PENDING = 'pending';
    public const STATUS_FAILED = 'failed';

    /**
     * Keys untuk parameter requiredKeys[] pada request do-ocr vision.
     */
    public static function requiredKeys(): array
    {
        return self::KTP_KEYS;
    }

    /**
     * Normalisasi nama field: huruf kecil, karakter non-alfanumerik jadi underscore.
     * Contoh: "tempat/tgl lahir" -> "tempat_tgl_lahir", "gol. darah" -> "gol_darah".
     */
    public static function normalizeKey(string $key): string
    {
        $key = strtolower(trim($key));
        $key = (string) preg_replace('/[^a-z0-9]+/', '_', $key);
        return trim($key, '_');
    }

    /**
     * Daftar field output yang diizinkan (snake_case).
     *
     * @return array<string>
     */
    public static function allowedFields(): array
    {
        return array_map(fn (string $key) => self::normalizeKey($key), self::KTP_KEYS);
    }

    /**
     * Pemetaan extractedTexts dari vision menjadi field terstruktur.
     *
     * Mendukung dua bentuk payload vision:
     *  - list: [{textKey: "...", textValue: "..."}, ...]
     *  - map:  {textKey: textValue, ...}
     * Elemen boleh berupa array maupun stdClass (hasil json_decode).
     *
     * @param mixed $extracted_texts isi OcrResponse::getData()['extractedTexts']
     * @return array<string, string> field terstruktur (whitelist, snake_case)
     */
    public static function mapExtractedTexts(mixed $extracted_texts): array
    {
        if (empty($extracted_texts)) {
            return [];
        }

        $allowed = array_flip(self::allowedFields());
        $pairs = self::toKeyValuePairs($extracted_texts);

        $fields = [];
        foreach ($pairs as $key => $value) {
            if (!is_string($key) || !is_scalar($value)) {
                continue;
            }
            $normalized = self::normalizeKey($key);
            if (!isset($allowed[$normalized])) {
                continue;
            }
            $fields[$normalized] = (string) $value;
        }

        // pertahankan urutan field sesuai urutan standar KTP
        $ordered = [];
        foreach (self::allowedFields() as $field) {
            if (array_key_exists($field, $fields)) {
                $ordered[$field] = $fields[$field];
            }
        }

        return $ordered;
    }

    /**
     * Konversi status upstream (WAITING|PROCESSING|DONE|FAILURE)
     * menjadi status API: pending|done|failed.
     */
    public static function mapStatus(?string $upstream_status): string
    {
        return match (strtoupper((string) $upstream_status)) {
            'DONE' => self::STATUS_DONE,
            'WAITING', 'PROCESSING' => self::STATUS_PENDING,
            default => self::STATUS_FAILED,
        };
    }

    /**
     * Menyeragamkan kedua bentuk payload extractedTexts menjadi pasangan key => value.
     *
     * @param mixed $extracted_texts
     * @return array<string, mixed>
     */
    private static function toKeyValuePairs(mixed $extracted_texts): array
    {
        if (is_object($extracted_texts)) {
            $extracted_texts = get_object_vars($extracted_texts);
        }
        if (!is_array($extracted_texts)) {
            return [];
        }

        foreach ($extracted_texts as $item) {
            if (self::hasTextKey($item)) {
                return self::fromListFormat($extracted_texts);
            }
        }

        // format map: textKey => textValue
        return $extracted_texts;
    }

    private static function hasTextKey(mixed $item): bool
    {
        if (is_object($item)) {
            return isset($item->textKey);
        }
        if (is_array($item)) {
            return isset($item['textKey']);
        }
        return false;
    }

    private static function fromListFormat(array $items): array
    {
        $pairs = [];
        foreach ($items as $item) {
            $obj = is_object($item) ? get_object_vars($item) : (is_array($item) ? $item : null);
            if ($obj === null || !isset($obj['textKey'])) {
                continue;
            }
            $pairs[$obj['textKey']] = $obj['textValue'] ?? null;
        }
        return $pairs;
    }
}
