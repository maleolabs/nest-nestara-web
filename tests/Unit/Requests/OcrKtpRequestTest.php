<?php

namespace Tests\Unit\Requests;

use Illuminate\Http\Testing\FileFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use PHPUnit\Framework\TestCase;

final class OcrKtpRequestTest extends TestCase
{
    private Factory $validatorFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validatorFactory = new Factory(
            new Translator(new ArrayLoader(), 'en')
        );
    }

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $rules = (new \App\Http\Requests\Api\OcrKtpRequest())->rules();

        return $this->validatorFactory->make($data, $rules);
    }

    private function fakeImage(string $name = 'ktp.jpg'): UploadedFile
    {
        return (new FileFactory())->image($name);
    }

    /**
     * JPEG asli yang diperbesar ke ukuran target (KB) dengan padding
     * setelah marker EOI, sehingga tetap terdeteksi sebagai image/jpeg.
     * Dipakai untuk menguji batas 'max' secara presisi tanpa merusak
     * validitas konten gambar.
     */
    private function jpegOfSizeInKb(int $kilobytes): UploadedFile
    {
        $jpeg = $this->fakeImage()->getContent();
        $padding = str_repeat("\0", max(0, $kilobytes * 1024 - strlen($jpeg)));

        return UploadedFile::fake()->createWithContent('ktp.jpg', $jpeg . $padding);
    }

    public function test_valid_image_passes_validation(): void
    {
        $validator = $this->validate(['image' => $this->fakeImage()]);

        $this->assertTrue($validator->passes());
    }

    public function test_missing_image_fails_validation(): void
    {
        $validator = $this->validate([]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('Required', $validator->failed()['image'] ?? []);
    }

    public function test_non_image_file_fails_validation(): void
    {
        $file = (new FileFactory())->createWithContent('ktp.pdf', '%PDF-1.4 dummy');

        $validator = $this->validate(['image' => $file]);

        $this->assertTrue($validator->fails());
    }

    public function test_pdf_error_explicitly_says_pdf_is_unsupported(): void
    {
        $file = (new FileFactory())->createWithContent('ktp.pdf', '%PDF-1.4 dummy');
        $validator = $this->validate(['image' => $file]);

        $this->assertTrue($validator->fails());
        $this->assertStringContainsString(
            'PDF tidak didukung',
            (new \App\Http\Requests\Api\OcrKtpRequest())->messages()['image.mimetypes']
        );
    }

    public function test_disallowed_mime_type_fails_validation(): void
    {
        $file = UploadedFile::fake()->createWithContent('ktp.gif', base64_decode('R0lGODlhAQABAAAAACw='));

        $validator = $this->validate(['image' => $file]);

        $this->assertTrue($validator->fails());
    }

    public function test_webp_image_fails_validation(): void
    {
        // konten webp valid (magic RIFF/WEBP) tapi tidak ada di whitelist mimes
        $webp = 'RIFF' . pack('N', 20) . 'WEBPVP8 ';
        $file = UploadedFile::fake()->createWithContent('ktp.webp', $webp);

        $validator = $this->validate(['image' => $file]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('Mimes', $validator->failed()['image'] ?? []);
    }

    public function test_oversized_image_fails_validation(): void
    {
        // max:3072 KB (3MB), file 4MB harus gagal
        $file = $this->jpegOfSizeInKb(4096);

        $validator = $this->validate(['image' => $file]);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('Max', $validator->failed()['image'] ?? []);
    }

    public function test_image_around_2_5mb_passes_validation(): void
    {
        // file ~2.5MB masih di bawah batas 3MB dan harus lolos
        $file = $this->jpegOfSizeInKb(2560);

        $validator = $this->validate(['image' => $file]);

        $this->assertTrue($validator->passes());
    }
}
