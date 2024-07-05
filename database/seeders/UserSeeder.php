<?php

namespace Database\Seeders;

use App\Models\User;
use App\utils\libs\ImageGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_name = fake()->name();
        $admin_foto = ImageGenerator::letterImage(substr($admin_name, 0, 1), 'public/users');
        $admin = User::create([
            'name' => $admin_name,
            'email' => 'admin@pmptsp.com',
            'password' => Hash::make('password'),
            'foto' => $admin_foto
        ]);
        $pengelola_layanan_name = fake()->name();
        $pengelola_layanan_foto = ImageGenerator::letterImage(substr($pengelola_layanan_name, 0, 1), 'public/users');
        $pengelola_layanan = User::create([
            'name' => $pengelola_layanan_name,
            'email' => 'pengelola@pmptsp.com',
            'password' => Hash::make('password'),
            'foto' => $pengelola_layanan_foto
        ]);
        $pegawai_name = fake()->name();
        $pegawai_foto = ImageGenerator::letterImage(substr($pegawai_name, 0, 1), 'public/users');
        $pegawai = User::create([
            'name' => $pegawai_name,
            'email' => 'pegawai@pmptsp.com',
            'password' => Hash::make('password'),
            'foto' => $pegawai_foto
        ]);
        $pemohon_name = fake()->name();
        $pemohon_foto = ImageGenerator::letterImage(substr($pemohon_name, 0, 1), 'public/users');
        $pemohon = User::create([
            'name' => $pemohon_name,
            'email' => 'pemohon@pmptsp.com',
            'password' => Hash::make('password'),
            'foto' => $pemohon_foto
        ]);
        
        $admin_role = Role::create([
            'name' => 'admin',
        ]);
        $pengelola_layanan_role = Role::create([
            'name' => 'pengelola_layanan',
        ]);
        $pegawai_role = Role::create([
            'name' => 'pegawai',
        ]);
        $pemohon_role = Role::create([
            'name' => 'pemohon',
        ]);

        $admin->assignRole($admin_role);
        $pengelola_layanan->assignRole($pengelola_layanan_role);
        $pegawai->assignRole($pegawai_role);
        $pemohon->assignRole($pemohon_role);
    }
   
}