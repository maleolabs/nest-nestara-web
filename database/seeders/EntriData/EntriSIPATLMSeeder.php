<?php

namespace Database\Seeders\EntriData;

use App\Models\EntriData;
use App\Models\JenisPerizinan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntriSIPATLMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $izin = JenisPerizinan::where('nama', 'SURAT IZIN KERJA AHLI TEKNOLOGI LABORATORIUM MEDIK (SIP-ATLM)')->first();
        if (!$izin)
            dd('izin tidak ditemukan!');

        EntriData::where('jenis_perizinan_id', $izin->id)->delete();

        EntriData::create([
            'jenis_perizinan_id' => $izin->id,
            'nama' => 'tempat tanggal lahir',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin->id,
            'nama' => 'nomor str atlm',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin->id,
            'nama' => 'tempat praktik',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin->id,
            'nama' => 'alamat tempat praktik',
            'tipe' => 'textarea',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin->id,
            'nama' => 'masa berlaku str atlm',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin->id,
            'nama' => 'tanggal penetapan',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
    }
}
