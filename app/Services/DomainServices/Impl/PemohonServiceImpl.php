<?php 

namespace App\Services\DomainServices\Impl;

use App\Models\Pemohon;
use App\Services\DomainServices\PemohonService;

class PemohonServiceImpl implements PemohonService
{

    public function save(array $data_pemohon, int $user_id = null): ?Pemohon
    {
        if ($user_id != null)
            $data_pemohon['user_id'] = $user_id;
        return Pemohon::create($data_pemohon);
    }

    public function update(int $id, array $data_pemohon): ?Pemohon
    {
        Pemohon::find($id)->update($data_pemohon);
        return Pemohon::find($id);
    }

    public function find(int $id): ?Pemohon
    {
        return Pemohon::find($id);
    }

    public function findByNomorIdOrNamaPemohon(string $cari): ?Pemohon
    {
        return Pemohon::where('nomor_id', $cari)->orWhere('nama_pemohon', $cari)->first();
    }

    public function getAll($search = ''): array
    {
        $semua_pemohon = Pemohon::where('nomor_id', 'LIKE', '%' . $search . '%')
        ->orWhereRaw('LOWER(nama_pemohon) LIKE ?', ['%' . strtolower($search) . '%'])
        ->get();
        return $semua_pemohon->all();
    }

    public function getByNomorIdOrNamaPemohon(string $cari): array
    {
        return Pemohon::where('nomor_id', 'LIKE', '%' . $cari . '%')->orWhere('nama_pemohon', 'LIKE', '%' . $cari . '%')->get()->toArray();
    }

    public function delete(int $id): bool
    {
        $pemohon = Pemohon::find($id);
        if (!$pemohon)
            return false;
        return $pemohon->delete();
    }

}