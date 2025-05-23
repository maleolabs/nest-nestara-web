<?php

namespace App\Imports\Perizinan;

use App\Services\DomainServices\SyaratIzinService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class JenisPerizinanSyaratIzinImport implements ToCollection
{
    
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if ($row[1] == null)
                dd($row[0] . ': tidak ada persyaratan');
            $this->syaratIzinService()->assignSyaratToJenisByName($row[1], $row[0]);
        }
    }

    private function syaratIzinService(): SyaratIzinService
    {
        return app(SyaratIzinService::class);
    }
    
}