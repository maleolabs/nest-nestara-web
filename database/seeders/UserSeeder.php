<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Services\DomainServices\UserService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_name = fake()->name();
        $admin = $this->userService()->save(null, ['name' => $admin_name, 'username' => 'administrator', 'password' => 'password']);

        $pengelola_layanan_name = fake()->name();
        $pengelola_layanan = $this->userService()->save(null, ['name' => $pengelola_layanan_name, 'username' => 'pegawai1', 'password' => 'password']);
        Pegawai::create([
            'user_id' => $pengelola_layanan->id,
            'nama' => $pengelola_layanan_name,
            'jabatan' => 'pegawai'
        ]);

        $pegawai_name = fake()->name();
        $pegawai = $this->userService()->save(null, ['name' => $pegawai_name, 'username' => 'pegawai2', 'password' => 'password']);
        Pegawai::create([
            'user_id' => $pegawai->id,
            'nama' => $pegawai_name,
            'jabatan' => 'pegawai'
        ]);

        $pemohon_name = fake()->name();
        $pemohon = $this->userService()->save(null, ['name' => $pemohon_name, 'username' => 'pemohon', 'password' => 'password']);
        
        $admin_role = Role::create([
            'name' => ADMIN_ROLE,
        ]);
        $kabid_role = Role::create([
            'name' => KABID_ROLE
        ]);
        $pengelola_layanan_role = Role::create([
            'name' => FRONT_OFFICE_ROLE,
        ]);
        $pegawai_role = Role::create([
            'name' => BACK_OFFICE_ROLE,
        ]);
        $pemohon_role = Role::create([
            'name' => PEMOHON_ROLE,
        ]);

        $admin->assignRole($admin_role);
        $pengelola_layanan->assignRole($pengelola_layanan_role);
        $pegawai->assignRole($pegawai_role);
        $pemohon->assignRole($pemohon_role);
    }

    private function userService(): UserService
    {
        return app(UserService::class);
    }
   
}