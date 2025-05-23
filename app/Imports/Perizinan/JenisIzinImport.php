<?php

namespace App\Imports\Perizinan;

use App\Models\JenisPerizinan;
use App\Services\DomainServices\JenisPerizinanService;
use Maatwebsite\Excel\Concerns\ToModel;

class JenisIzinImport implements ToModel
{

    protected JenisPerizinanService $jenisPerizinanService;

    public function __construct()
    {
        $this->jenisPerizinanService = app(JenisPerizinanService::class);
    }
    
    public function model(array $row)
    {
        return $this->jenisPerizinanService->save([
            'nama' => $row[0]
        ]);
    }
    
}