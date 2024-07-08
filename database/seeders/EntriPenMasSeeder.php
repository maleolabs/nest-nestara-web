<?php

namespace Database\Seeders;

use App\Models\EntriData;
use App\Models\JenisPerizinan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntriPenMasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $izin_penmas = JenisPerizinan::where('nama', 'IZIN PENELITIAN MAHASISWA')->first();
        if (!$izin_penmas)
            dd('izin penmas tidak ditemukan!');

        EntriData::where('jenis_perizinan_id', $izin_penmas->id)->delete();

        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'menimbang',
            'tipe' => 'textarea',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'judul penelitian',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'tujuan penelitian',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'tempat penelitian',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'waktu penelitian',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'bidang penelitian',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'status penelitian',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'penanggungjawab',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'anggota peneliti',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100,
            'is_multi_value' => true,
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'nama lembaga',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'tanggal penetapan',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_penmas->id,
            'nama' => 'berlaku sampai',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
    }   
    
}