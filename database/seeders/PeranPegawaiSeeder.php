<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\PeranPegawai;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeranPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peran_pegawai = [
            'penandatangan',
            'timteknis',
        ];
        $all_pegawai = Pegawai::factory(5)->createMany();
        foreach ($all_pegawai as $pegawai) {
            PeranPegawai::create([
                'pegawai_id' => $pegawai->id,
                'peran' => $peran_pegawai[rand(0,1)],
            ]);
        }
    }
}
