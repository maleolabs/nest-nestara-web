<?php

namespace App\Services\DomainServices\Helpers;

/**
 * Memetakan hasil OCR KTP dari vision service menjadi struktur field
 * yang aman dikirim ke client.
 *
 * Sumber nama field: nestara-vision-saas
 * (opt/app/ocr/extractor/ktp_extractor.py -> EXPECTED_KEYS).
 * Output dibatasi whitelist sehingga kredensial tidak pernah ikut.
 *
 * Enhancements:
 * - Document-type aware (KTP/KK/SIM/PASPOR/GENERIC) — field whitelist dinamis
 * - Header tanpa ':' (PROVINSI GORONTALO) sudah di-handle di vision, tapi mapper
 *   tetap toleran jika vision mengirim provinsi/kabupaten sebagai key terpisah.
 * - Untuk region (provinsi, kabupaten, kecamatan, kelurahan) mengembalikan TEXT bersih;
 *   mobile/web yang melakukan resolve ID via AddressService (findByName + fuzzy).
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
        'kewarganegaraan', 'berlaku hingga',
    ];

    // Generic documents still use explicit fields; never pass through upstream metadata.
    public const GENERIC_KEYS = [
        'nama', 'nik', 'nomor', 'alamat', 'tempat/tgl lahir', 'tanggal lahir',
        'jenis kelamin', 'agama', 'pekerjaan', 'status', 'kewarganegaraan',
        'provinsi', 'kabupaten', 'kota', 'kecamatan', 'kelurahan', 'rt/rw',
    ];

    public const STATUS_DONE = 'accepted';

    public const STATUS_PENDING = 'pending';

    public const STATUS_FAILED = 'failed';

    public const STATUS_MANUAL_REVIEW = 'manual_review';

    public const STATUS_REJECTED = 'rejected';

    /** Document/status pair accepted by mobile profile gate. */
    public static function unlocksProfile(string $status, array $result): bool
    {
        return $status === self::STATUS_DONE;
    }

    public static function requiredKeys(): array
    {
        return self::KTP_KEYS;
    }

    public static function normalizeKey(string $key): string
    {
        $key = strtolower(trim($key));
        $key = (string) preg_replace('/[^a-z0-9]+/', '_', $key);

        return trim($key, '_');
    }

    public static function allowedFields(): array
    {
        return array_map(fn (string $key) => self::normalizeKey($key), self::KTP_KEYS);
    }

    /**
     * Pemetaan extractedTexts dari vision menjadi field terstruktur.
     * Mendukung doc_type awareness: jika vision mengirim _document_type, whitelist
     * diperluas untuk generic.
     */
    public static function mapExtractedTexts(mixed $extracted_texts): array
    {
        if (empty($extracted_texts)) {
            return [];
        }

        $pairs = self::toKeyValuePairs($extracted_texts);

        // detect doc type from payload if present
        $docType = null;
        foreach ($pairs as $k => $v) {
            if (strtolower((string) $k) === '_document_type' && is_string($v)) {
                $docType = strtoupper(trim($v));
                break;
            }
        }

        $isGeneric = $docType !== null && $docType !== 'KTP';

        // Build allowed list
        $allowed = array_flip(self::allowedFields());
        // For generic, also allow GENERIC_KEYS normalized
        if ($isGeneric) {
            foreach (self::GENERIC_KEYS as $gk) {
                $allowed[self::normalizeKey($gk)] = true;
            }
        }

        $fields = [];
        $rawText = null;

        foreach ($pairs as $key => $value) {
            if ($key === '_document_type' || $key === '_document_type_normalized') {
                continue;
            }
            if ($key === 'raw_text') {
                $rawText = $value;

                continue;
            }
            if (! is_string($key) || ! is_scalar($value)) {
                continue;
            }
            $valueStr = trim((string) $value);
            if ($valueStr === '') {
                continue;
            }

            $normalized = self::normalizeKey($key);

            if (! isset($allowed[$normalized])) {
                continue;
            }

            // Clean region values: title case, remove OCR artifacts like "GORGNTALG"
            if (in_array($normalized, ['provinsi', 'kabupaten', 'kota', 'kecamatan', 'kelurahan', 'alamat'], true)) {
                $valueStr = self::cleanRegionValue($valueStr);
            }

            // NIK validation: keep only if 16 digits or keep as is for manual correction
            if ($normalized === 'nik') {
                $digits = preg_replace('/[^0-9]/', '', $valueStr);
                if (strlen($digits) >= 14) {
                    $valueStr = substr($digits, 0, 16);
                }
            }

            if (! isset($fields[$normalized]) || strlen($valueStr) > strlen($fields[$normalized])) {
                $fields[$normalized] = $valueStr;
            }
        }

        // If generic and no field passed whitelist, try to parse raw_text
        if (empty($fields) && $rawText !== null) {
            // fallback: return raw_text as single field for manual review
            return ['raw_text' => (string) $rawText, '_document_type' => $docType ?? 'GENERIC'];
        }

        // Preserve _document_type for client to know how to handle region IDs
        if ($docType !== null) {
            $fields['_document_type'] = $docType;
        }

        // order KTP fields first, then rest
        $ordered = [];
        foreach (self::allowedFields() as $field) {
            if (array_key_exists($field, $fields)) {
                $ordered[$field] = $fields[$field];
            }
        }
        // append any extra generic fields not in KTP order
        foreach ($fields as $k => $v) {
            if (! array_key_exists($k, $ordered)) {
                $ordered[$k] = $v;
            }
        }

        return $ordered;
    }

    private static function cleanRegionValue(string $val): string
    {
        $val = trim($val);
        // remove leading :.-
        $val = preg_replace('/^[\s:;\-\.]+/', '', $val);
        $val = preg_replace('/\s+/', ' ', $val);
        $val = trim($val, " :.-\t");
        // fix common OCR province typos
        $upper = strtoupper($val);
        $replacements = [
            'GORGNTALG' => 'GORONTALO',
            'GORGNTALO' => 'GORONTALO',
            'GOROTALO' => 'GORONTALO',
        ];
        foreach ($replacements as $wrong => $right) {
            if (str_contains($upper, $wrong)) {
                $val = str_ireplace($wrong, $right, $val);
            }
        }

        // Preserve OCR casing; API consumers and existing KTP contract expect source text.
        return $val;
    }

    public static function mapStatus(?string $upstream_status): string
    {
        return match (strtoupper((string) $upstream_status)) {
            'ACCEPTED', 'DONE' => self::STATUS_DONE,
            'REJECTED', 'UNSUPPORTED_DOCUMENT', 'UNSUPPORTED' => self::STATUS_REJECTED,
            'FAILED', 'FAILURE', 'ERROR' => self::STATUS_FAILED,
            'MANUAL_REVIEW', 'REVIEW', 'NEEDS_REVIEW' => self::STATUS_MANUAL_REVIEW,
            'WAITING', 'PROCESSING' => self::STATUS_PENDING,
            // Unknown or missing upstream values are terminal failures. Pending is
            // reserved for statuses explicitly declared in-flight by Vision.
            default => self::STATUS_FAILED,
        };
    }

    private static function toKeyValuePairs(mixed $extracted_texts): array
    {
        if (is_object($extracted_texts)) {
            $extracted_texts = get_object_vars($extracted_texts);
        }
        if (! is_array($extracted_texts)) {
            return [];
        }
        foreach ($extracted_texts as $item) {
            if (self::hasTextKey($item)) {
                return self::fromListFormat($extracted_texts);
            }
        }

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
            if ($obj === null || ! isset($obj['textKey'])) {
                continue;
            }
            $pairs[$obj['textKey']] = $obj['textValue'] ?? null;
        }

        return $pairs;
    }
}
