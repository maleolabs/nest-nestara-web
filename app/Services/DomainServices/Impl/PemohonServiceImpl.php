<?php 

namespace App\Services\DomainServices\Impl;

use App\Models\Pemohon;
use App\Services\DomainServices\PemohonService;

class PemohonServiceImpl implements PemohonService
{

    public function save(array $data_pemohon, ?int $user_id = null): ?Pemohon
    {
        if ($user_id != null)
            $data_pemohon['user_id'] = $user_id;
        return Pemohon::create($data_pemohon);
    }

    public function findOrphanByNomorId(string $nomor_id): ?Pemohon
    {
        // pemohon yatim = baris pemohon yang user_id-nya NULL
        // (pemilik akun lamanya sudah dihapus, FK SET NULL)
        return Pemohon::whereNull('user_id')
            ->where('nomor_id', $nomor_id)
            ->first();
    }

    public function adopt(int $id, int $user_id, array $data_pemohon): ?Pemohon
    {
        $pemohon = Pemohon::find($id);
        if (!$pemohon)
            return null;

        // klaim pemohon yatim: kaitkan ke akun baru lalu perbarui datanya
        // dengan input terbaru (orang yang sama daftar ulang)
        $data_pemohon['user_id'] = $user_id;
        $pemohon->update($data_pemohon);

        return $pemohon->fresh();
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

    public function getAll($search = '', ?int $limit = null): array
    {
        // grouping diperbaiki: orWhere sebelumnya merusak filter nomor_id
        $query = Pemohon::where(function ($query) use ($search) {
            $query->where('nomor_id', 'LIKE', '%' . $search . '%')
                ->orWhereRaw('LOWER(nama_pemohon) LIKE ?', ['%' . strtolower($search) . '%']);
        });

        // safety cap — hindari load seluruh tabel tanpa batas
        $query->take($limit ?: 500);

        return $query->get()->all();
    }

    public function getPaginated(string $search = '', int $per_page = 20, int $page = 1, string $page_name = 'page'): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Pemohon::where(function ($query) use ($search) {
            $query->where('nomor_id', 'LIKE', '%' . $search . '%')
                ->orWhereRaw('LOWER(nama_pemohon) LIKE ?', ['%' . strtolower($search) . '%']);
        })
            ->orderBy('created_at', 'desc')
            ->paginate($per_page, ['*'], $page_name, $page);
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