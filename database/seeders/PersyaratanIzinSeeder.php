<?php

namespace Database\Seeders;

use App\Models\SyaratIzin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersyaratanIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SyaratIzin::create([
            'nama' => 'syarat izin satu'
        ]);
        SyaratIzin::create([
            'nama' => 'syarat izin dua'
        ]);
        SyaratIzin::create([
            'nama' => 'syarat izin tiga'
        ]);
        SyaratIzin::create([
            'nama' => 'syarat izin empat'
        ]);
        SyaratIzin::create([
            'nama' => 'syarat izin lima'
        ]);
    }
}
