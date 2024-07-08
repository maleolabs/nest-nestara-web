<?php

namespace Tests\Feature\DomainService;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\DomainServices\PemohonService;
use App\Models\Pemohon;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

class PemohonServiceTest extends TestCase
{
    
    public function test_save(): void
    {
        DB::statement("SET foreign_key_checks=0");
        Pemohon::truncate();
        DB::statement("SET foreign_key_checks=1");
        $data_pemohon = [
            'sumber_identitas' => 'ktp',
            'nomor_id' => '7498473987423',
            'nama_pemohon' => 'dummy',
            'no_telepon' => '08543873847',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan mangga',
        ];

        $pemohonService = app(PemohonService::class);
        $saved_data = $pemohonService->save($data_pemohon);
        assertNotNull($saved_data);
        assertIsArray($saved_data->toArray());
    }

    public function test_update()
    {
        $data_pemohon = [
            'sumber_identitas' => 'ktp',
            'nomor_id' => '74984739874231',
            'nama_pemohon' => 'dummy',
            'no_telepon' => '08543873847',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan mangga',
        ];

        $pemohonService = app(PemohonService::class);
        $saved_data = $pemohonService->save($data_pemohon);

        assertNotNull($saved_data);
        $data_update = [
            'nama_pemohon' => 'dummy update',
        ];
        $updated_data = $pemohonService->update($saved_data->id, $data_update);
        assertNotNull($updated_data);
        assertSame($data_update['nama_pemohon'], $updated_data->nama_pemohon);
    }

    public function test_find()
    {
        $data_pemohon = [
            'sumber_identitas' => 'ktp',
            'nomor_id' => '74984739874232',
            'nama_pemohon' => 'dummy2',
            'no_telepon' => '08543873847',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan mangga',
        ];

        $pemohonService = app(PemohonService::class);
        $saved_data = $pemohonService->save($data_pemohon);
        $find_pemohon = $pemohonService->find($saved_data->id);
        assertNotNull($find_pemohon);
        assertEquals($data_pemohon['nomor_id'], $find_pemohon->nomor_id);
    }

    public function test_find_by_nomor_id_or_nama_pemohon()
    {
        $data_pemohon = [
            'sumber_identitas' => 'ktp',
            'nomor_id' => '74984739874233',
            'nama_pemohon' => 'dummy3',
            'no_telepon' => '08543873847',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan mangga',
        ];

        $pemohonService = app(PemohonService::class);
        $saved_data = $pemohonService->save($data_pemohon);

        assertNotNull($saved_data);

        $find_data_by_nomor_id = $pemohonService->findByNomorIdOrNamaPemohon($saved_data->nomor_id);
        assertNotNull($find_data_by_nomor_id);
        assertSame($find_data_by_nomor_id->nomor_id, $data_pemohon['nomor_id']);
        $find_data_by_nama_pemohon = $pemohonService->findByNomorIdOrNamaPemohon($saved_data->nama_pemohon);
        assertNotNull($find_data_by_nama_pemohon);
        assertSame($find_data_by_nama_pemohon->nama_pemohon, $data_pemohon['nama_pemohon']);
    }

    public function test_get_by_nomor_id_or_nama_pemohon()
    {
        DB::statement("SET foreign_key_checks=0");
        Pemohon::truncate();
        DB::statement("SET foreign_key_checks=1");
        $data_pemohon = [
            'sumber_identitas' => 'ktp',
            'nomor_id' => '74984739874234',
            'nama_pemohon' => 'dummy4',
            'no_telepon' => '08543873847',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan mangga',
        ];
        $data_pemohon2 = [
            'sumber_identitas' => 'ktp',
            'nomor_id' => '74984739874235',
            'nama_pemohon' => 'dummy5',
            'no_telepon' => '08543873847',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan mangga',
        ];

        $pemohonService = app(PemohonService::class);
        $saved_data = $pemohonService->save($data_pemohon);
        $saved_data2 = $pemohonService->save($data_pemohon2);

        assertNotNull($saved_data);
        assertNotNull($saved_data2);

        $get_all_like_nomor_id = $pemohonService->getByNomorIdOrNamaPemohon(7498473987423);
        assertSame(2, count($get_all_like_nomor_id));
    }

    public function test_delete()
    {
        $data_pemohon = [
            'sumber_identitas' => 'ktp',
            'nomor_id' => '74984739874236',
            'nama_pemohon' => 'dummy3',
            'no_telepon' => '08543873847',
            'provinsi' => 75,
            'kabupaten' => 7504,
            'kecamatan' => 7504030,
            'kelurahan' => 7504030023,
            'detail_alamat' => 'jalan mangga',
        ];

        $pemohonService = app(PemohonService::class);
        $saved_data = $pemohonService->save($data_pemohon);
        assertNotNull($saved_data);
        assertTrue($pemohonService->delete($saved_data->id));
    }
   
}