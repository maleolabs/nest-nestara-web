<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemohonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'sumber_identitas' => $this->sumber_identitas,
            'nomor_id' => $this->nomor_id,
            'nama' => $this->nama_pemohon,
            'no_telepon' => $this->no_telepon,
            'alamat' => convert_address($this->getAlamat()),
            'detail_alamat' => $this->detail_alamat,
            'alamat_luar_negeri' => $this->alamat_luar_negeri
        ];
    }
}
