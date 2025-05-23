<?php

namespace Database\Seeders\EntriData;

use App\Models\EntriData;
use App\Models\JenisPerizinan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntriSIPPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $izin_sipp = JenisPerizinan::where('nama', 'SURAT IZIN PRAKTEK PERAWAT')->first();
        if (!$izin_sipp)
            dd('izin tidak ditemukan!');

        EntriData::where('jenis_perizinan_id', $izin_sipp->id)->delete();

        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'nama pemilik',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'tempat tanggal lahir',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'nomor rekomendasi',
            'tipe' => 'text',
            'mandatori' => false,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'tanggal rekomendasi',
            'tipe' => 'date',
            'mandatori' => false,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'nomor strb',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'tempat praktik',
            'tipe' => 'text',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'alamat tempat praktik',
            'tipe' => 'textarea',
            'mandatori' => true,
            'max' => 255
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'berlaku s/d tanggal',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
        EntriData::create([
            'jenis_perizinan_id' => $izin_sipp->id,
            'nama' => 'tanggal penetapan',
            'tipe' => 'date',
            'mandatori' => true,
            'max' => 100
        ]);
    }
}
