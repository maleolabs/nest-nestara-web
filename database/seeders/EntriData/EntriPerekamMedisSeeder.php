<?php

namespace Database\Seeders\EntriData;

use App\Models\EntriData;
use App\Models\JenisPerizinan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntriPerekamMedisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $izin_perekam_medis = JenisPerizinan::where('nama', 'SURAT IZIN KERJA PEREKAM MEDIS DAN INFORMASI KESEHATAN')->first();
        if (!$izin_perekam_medis)
            dd('izin penmas tidak ditemukan!');

        EntriData::where('jenis_perizinan_id', $izin_perekam_medis->id)->delete();

        EntriData::create([
            'jenis_perizinan_id' => $izin_perekam_medis->id,
            'nama' => 'tempat tanggal lahir',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_perekam_medis->id,
            'nama' => 'nomor str remik',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_perekam_medis->id,
            'nama' => 'nomor rekomendasi',
            'tipe' => 'text',
            'mandatori' => false,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_perekam_medis->id,
            'nama' => 'tempat praktik',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_perekam_medis->id,
            'nama' => 'alamat tempat praktik',
            'tipe' => 'textarea',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_perekam_medis->id,
            'nama' => 'berlaku s/d tanggal',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_perekam_medis->id,
            'nama' => 'tanggal penetapan',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
    }
}
