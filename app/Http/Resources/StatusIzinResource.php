<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusIzinResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nama' => $this->nama,
            'keterangan' => $this->keterangan,
            'detail_keterangan' => $this->detail_keterangan,
            'progres' => $this->progres,
            'created_at' => $this->created_at->translatedFormat('d F Y H:i'),
            'updated_at' => $this->updated_at->translatedFormat('d F Y H:i')
        ];
    }
}
