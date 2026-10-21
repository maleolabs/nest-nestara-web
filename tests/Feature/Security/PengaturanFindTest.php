<?php

namespace Tests\Feature\Security;

use App\Models\Pengaturan;
use App\Models\User;
use App\Services\DomainServices\PengaturanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regresi WEB-F03 (plan P2.3): PengaturanServiceImpl::find sempat men-query
 * kolom `id` (PK tabel) padahal yang dimaksud `user_id` (FK users, nullable).
 * Invarian: baris per-user menimpa baris global; user tanpa override → global.
 *
 * requires DB dengan migration terpasang (RefreshDatabase). Parent row users
 * dibuat asli karena pengaturan.user_id = FK users. Semua pemanggil
 * get_pengaturan saat ini memakai global (tanpa user_id), jadi tidak ada
 * caller yang meng-kompensasi bug ini.
 */
class PengaturanFindTest extends TestCase
{
    use RefreshDatabase;

    private User $userWithOverride;

    private User $userWithoutOverride;

    protected function setUp(): void
    {
        parent::setUp();

        // parent FK users asli (id dinamis, bukan literal 7/9)
        $this->userWithOverride = User::create([
            'name' => 'pemilik override',
            'username' => 'override-user',
            'password' => bcrypt('secret'),
        ]);
        $this->userWithoutOverride = User::create([
            'name' => 'tanpa override',
            'username' => 'plain-user',
            'password' => bcrypt('secret'),
        ]);
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
            'value' => 'override-key',
            'user_id' => $this->userWithOverride->id,
        ]);

        $found = $this->service()->find('ocr-api-key', $this->userWithOverride->id);

        $this->assertNotNull($found);
        $this->assertSame('override-key', $found->value);
    }

    public function test_find_falls_back_to_global_for_user_without_override(): void
    {
        Pengaturan::create([
            'group' => 'ocr-api-setting-group',
            'nama' => 'ocr-base-url',
            'value' => 'global-url',
            'user_id' => null,
        ]);

        // bug lama: where id=<user_id> → baris global (id kecil) tak terjangkau
        $found = $this->service()->find('ocr-base-url', $this->userWithoutOverride->id);

        $this->assertNotNull($found);
        $this->assertSame('global-url', $found->value);
    }

    public function test_find_without_user_id_returns_only_global(): void
    {
        Pengaturan::create(['group' => 'g', 'nama' => 'format-surat', 'value' => 'AA', 'user_id' => null]);
        Pengaturan::create(['group' => 'g', 'nama' => 'format-surat', 'value' => 'BB', 'user_id' => $this->userWithOverride->id]);

        $found = $this->service()->find('format-surat', null);

        $this->assertSame('AA', $found->value);
    }

    public function test_find_returns_null_when_nothing_matches(): void
    {
        Pengaturan::create(['group' => 'g', 'nama' => 'only-global', 'value' => 'x', 'user_id' => null]);

        $this->assertNull($this->service()->find('not-found', $this->userWithoutOverride->id));
        $this->assertNull($this->service()->find('not-found', null));
    }
}
