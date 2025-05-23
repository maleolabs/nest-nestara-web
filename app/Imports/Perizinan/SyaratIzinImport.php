<?php

namespace App\Imports\Perizinan;

use App\Models\SyaratIzin;
use Maatwebsite\Excel\Concerns\ToModel;

class SyaratIzinImport implements ToModel
{

    public function model(array $row)
    {
        $tipe = $row[1] ?? 'pdf';
        return new SyaratIzin([
            'nama' => $row[0],
            'tipe' => $tipe
        ]);
    }
    
}