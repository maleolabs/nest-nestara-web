<?php

namespace Tests\Unit\Services;

use App\Services\DomainServices\Helpers\KtpFieldMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class KtpFieldMapperTest extends TestCase
{
    public function test_maps_list_format_extracted_texts(): void
    {
        $extracted = [
            ['textKey' => 'nik', 'textValue' => '1234567890123456'],
            ['textKey' => 'nama', 'textValue' => 'BUDI SANTOSO'],
        ];

        $fields = KtpFieldMapper::mapExtractedTexts($extracted);

        $this->assertSame('1234567890123456', $fields['nik']);
        $this->assertSame('BUDI SANTOSO', $fields['nama']);
    }

    public function test_maps_list_format_with_stdclass_items(): void
    {
        $item1 = new \stdClass;
        $item1->textKey = 'provinsi';
        $item1->textValue = 'JAWA TIMUR';
        $item2 = new \stdClass;
        $item2->textKey = 'agama';
        $item2->textValue = 'ISLAM';

        $fields = KtpFieldMapper::mapExtractedTexts([$item1, $item2]);

        $this->assertSame('JAWA TIMUR', $fields['provinsi']);
        $this->assertSame('ISLAM', $fields['agama']);
    }

    public function test_maps_map_format_extracted_texts(): void
    {
        $extracted = [
            'nik' => '1234567890123456',
            'jenis kelamin' => 'LAKI-LAKI',
            'gol. darah' => 'O',
        ];

        $fields = KtpFieldMapper::mapExtractedTexts((object) $extracted);

        $this->assertSame('1234567890123456', $fields['nik']);
        $this->assertSame('LAKI-LAKI', $fields['jenis_kelamin']);
        $this->assertSame('O', $fields['gol_darah']);
    }

    public function test_normalizes_field_names_to_snake_case(): void
    {
        $extracted = [
            ['textKey' => 'tempat/tgl lahir', 'textValue' => 'SURABAYA, 01-01-1990'],
            ['textKey' => 'status perkawinan', 'textValue' => 'KAWIN'],
            ['textKey' => 'rt/rw', 'textValue' => '003/005'],
            ['textKey' => 'berlaku hingga', 'textValue' => 'SEUMUR HIDUP'],
        ];

        $fields = KtpFieldMapper::mapExtractedTexts($extracted);

        $this->assertSame('SURABAYA, 01-01-1990', $fields['tempat_tgl_lahir']);
        $this->assertSame('KAWIN', $fields['status_perkawinan']);
        $this->assertSame('003/005', $fields['rt_rw']);
        $this->assertSame('SEUMUR HIDUP', $fields['berlaku_hingga']);
    }

    public function test_output_follows_standard_ktp_key_order(): void
    {
        $extracted = [
            ['textKey' => 'pekerjaan', 'textValue' => 'WIRASWASTA'],
            ['textKey' => 'nama', 'textValue' => 'BUDI'],
            ['textKey' => 'nik', 'textValue' => '1234567890123456'],
        ];

        $fields = KtpFieldMapper::mapExtractedTexts($extracted);

        $this->assertSame(['nik', 'nama', 'pekerjaan'], array_keys($fields));
    }

    public function test_ignores_unknown_keys_including_credential_like_keys(): void
    {
        $extracted = [
            ['textKey' => 'nik', 'textValue' => '1234567890123456'],
            ['textKey' => 'api_key', 'textValue' => 'secret-key'],
            ['textKey' => 'base_url', 'textValue' => 'http://localhost:8080/api/'],
            ['textKey' => 'clientId', 'textValue' => 'internal-client-id'],
            ['textKey' => 'requestId', 'textValue' => '42'],
        ];

        $fields = KtpFieldMapper::mapExtractedTexts($extracted);

        $this->assertSame(['nik' => '1234567890123456'], $fields);
        $this->assertArrayNotHasKey('api_key', $fields);
        $this->assertArrayNotHasKey('base_url', $fields);
    }

    public function test_empty_or_invalid_input_returns_empty_array(): void
    {
        $this->assertSame([], KtpFieldMapper::mapExtractedTexts(null));
        $this->assertSame([], KtpFieldMapper::mapExtractedTexts([]));
        $this->assertSame([], KtpFieldMapper::mapExtractedTexts('bukan-data'));
    }

    public function test_required_keys_match_upstream_expected_ktp_keys(): void
    {
        $required = KtpFieldMapper::requiredKeys();

        $this->assertContains('nik', $required);
        $this->assertContains('nama', $required);
        $this->assertContains('tempat/tgl lahir', $required);
        $this->assertContains('status perkawinan', $required);
        $this->assertCount(17, $required);
    }

    public function test_normalize_key_converts_to_snake_case(): void
    {
        $this->assertSame('tempat_tgl_lahir', KtpFieldMapper::normalizeKey('Tempat/Tgl Lahir'));
        $this->assertSame('gol_darah', KtpFieldMapper::normalizeKey('Gol. Darah'));
        $this->assertSame('nik', KtpFieldMapper::normalizeKey(' NIK '));
    }

    public function test_maps_upstream_status_to_api_status(): void
    {
        $this->assertSame(KtpFieldMapper::STATUS_PENDING, KtpFieldMapper::mapStatus('WAITING'));
        $this->assertSame(KtpFieldMapper::STATUS_PENDING, KtpFieldMapper::mapStatus('PROCESSING'));
        $this->assertSame(KtpFieldMapper::STATUS_DONE, KtpFieldMapper::mapStatus('DONE'));
        $this->assertSame(KtpFieldMapper::STATUS_FAILED, KtpFieldMapper::mapStatus('FAILURE'));
        $this->assertSame(KtpFieldMapper::STATUS_PENDING, KtpFieldMapper::mapStatus(null));
        $this->assertSame(KtpFieldMapper::STATUS_PENDING, KtpFieldMapper::mapStatus('UNKNOWN'));
    }

    public function test_maps_status_case_insensitive(): void
    {
        $this->assertSame(KtpFieldMapper::STATUS_DONE, KtpFieldMapper::mapStatus('done'));
        $this->assertSame(KtpFieldMapper::STATUS_PENDING, KtpFieldMapper::mapStatus('processing'));
    }

    #[DataProvider('identityDocumentTypes')]
    public function test_preserves_identity_document_classification(string $type): void
    {
        $fields = KtpFieldMapper::mapExtractedTexts([
            '_document_type' => $type,
            'nama' => 'BUDI',
        ]);

        $this->assertSame(strtoupper($type), $fields['_document_type']);
        $this->assertSame('BUDI', $fields['nama']);
    }

    public static function identityDocumentTypes(): array
    {
        return [['KTP'], ['SIM'], ['UNKNOWN']];
    }

    public function test_generic_documents_never_expose_secrets_or_upstream_metadata(): void
    {
        $fields = KtpFieldMapper::mapExtractedTexts([
            '_document_type' => 'GENERIC',
            'nama' => 'BUDI',
            'password' => 'pw',
            'token' => 'tok',
            'secret' => 'sec',
            'credential' => 'cred',
            'arbitrary_upstream_field' => 'leak',
        ]);

        $this->assertSame(['nama' => 'BUDI', '_document_type' => 'GENERIC'], $fields);
    }
}
