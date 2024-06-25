<?php

namespace Database\Seeders;

use App\Imports\Perizinan\JenisIzinImport;
use App\Imports\Perizinan\JenisPerizinanSyaratIzinImport;
use App\Imports\Perizinan\SyaratIzinImport;
use App\Models\JenisPerizinan;
use App\Models\SyaratIzin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PersyaratanIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("SET foreign_key_checks=0");
        SyaratIzin::truncate();
        JenisPerizinan::truncate();
        DB::statement("SET foreign_key_checks=1");

        $syarat_import_file_path = 'C:\Dev\Personal-Project\Web Scraping\python\distinct_data_output.xlsx';
        Excel::import(new SyaratIzinImport, $syarat_import_file_path);

        $jenis_import_file_path = 'C:\users\marij_mokoginta\Downloads\jenis-perizinan-2.xlsx';
        Excel::import(new JenisIzinImport, $jenis_import_file_path);

        $jenis_syarat_file_path = 'C:\Dev\Personal-Project\Web Scraping\python\detail_data_output.xlsx';
        Excel::import(new JenisPerizinanSyaratIzinImport, $jenis_syarat_file_path);
    }
}
