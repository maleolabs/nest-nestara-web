<?php

namespace Tests\Feature\DomainService;

use App\Services\DomainServices\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

use function PHPUnit\Framework\assertSame;

class RoleServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_by_names(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'pemohon', 'guard_name' => 'web']);
        $roles = $this->roleService()->getAllByNames(['admin', 'pemohon'], true);
        assertSame(['admin', 'pemohon'], $roles);
    }

    private function roleService(): RoleService
    {
        return app(RoleService::class);
    }
}
