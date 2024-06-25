<?php

namespace App\Imports\Perizinan;

use App\Models\JenisPerizinan;
use Maatwebsite\Excel\Concerns\ToModel;

class JenisIzinImport implements ToModel
{
    
    public function model(array $row)
    {
        return new JenisPerizinan([
            'nama' => $row[0]
        ]);
    }
    
}