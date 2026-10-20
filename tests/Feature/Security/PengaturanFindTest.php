<?php

namespace Tests\Feature\Security;

use App\Models\Pengaturan;
use App\Services\DomainServices\PengaturanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi WEB-F03 (plan P2.3): PengaturanServiceImpl::find sempat men-query
 * kolom `id` (PK tabel) padahal yang dimaksud `user_id` (FK users, nullable).
 * Invarian: baris per-user menimpa baris global; user tanpa override → global.
 *
 * requires DB (MySQL) — MySQL tidak tersedia di sandbox; hanya berjalan di
 * env dengan DB. Semua pemanggil get_pengaturan saat ini memakai global
 * (tanpa user_id), jadi tidak ada caller yang meng-kompensasi bug ini.
 */
class PengaturanFindTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Pengaturan::query()->delete();
        parent::tearDown();
    }

    private function service(): PengaturanService
    {
        return app(PengaturanService::class);
    }

    public function test_find_returns_user_override_when_exists(): void
    {
        Pengaturan::create([
            'group' => 'ocr-api-setting-group',
            'nama' => 'ocr-api-key',
            'value' => 'global-key',
            'user_id' => null,
        ]);
        Pengaturan::create([
            'group' => 'ocr-api-setting-group',
            'nama' => 'ocr-api-key',
            'value' => 'user-7-key',
            'user_id' => 7,
        ]);

        $found = $this->service()->find('ocr-api-key', 7);

        $this->assertNotNull($found);
        $this->assertSame('user-7-key', $found->value);
    }

    public function test_find_falls_back_to_global_for_user_without_override(): void
    {
        Pengaturan::create([
            'group' => 'ocr-api-setting-group',
            'nama' => 'ocr-base-url',
            'value' => 'global-url',
            'user_id' => null,
        ]);

        $found = $this->service()->find('ocr-base-url', 8);

        $this->assertNotNull($found);
        $this->assertSame('global-url', $found->value);
    }

    public function test_find_without_user_id_returns_only_global(): void
    {
        Pengaturan::create(['group' => 'g', 'nama' => 'format-surat', 'value' => 'AA', 'user_id' => null]);
        Pengaturan::create(['group' => 'g', 'nama' => 'format-surat', 'value' => 'BB', 'user_id' => 9]);

        $found = $this->service()->find('format-surat', null);

        $this->assertSame('AA', $found->value);
    }

    public function test_find_returns_null_when_nothing_matches(): void
    {
        Pengaturan::create(['group' => 'g', 'nama' => 'only-global', 'value' => 'x', 'user_id' => null]);

        $this->assertNull($this->service()->find('not-found', 5));
        $this->assertNull($this->service()->find('not-found', null));
    }
}
