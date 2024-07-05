<?php

namespace Tests\Feature\DomainService;

use App\Services\DomainServices\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertSame;

class RoleServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_by_names(): void
    {
        $roles = $this->roleService()->getAllByNames(['admin', 'pemohon'], true);
        assertSame(['admin', 'pemohon'], $roles);
    }

    private function roleService(): RoleService
    {
        return app(RoleService::class);
    }
}
