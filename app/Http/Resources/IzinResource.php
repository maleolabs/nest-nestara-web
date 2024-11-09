<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IzinResource extends JsonResource
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
            'jenis_perizinan' => new JenisPerizinanResource($this->jenis_perizinan),
            'status' => StatusIzinResource::collection($this->status_izin),
            'jenis_permohonan' => $this->jenis_permohonan,
            'no_pendaftaran' => $this->no_pendaftaran,
            'lokasi_izin' => $this->lokasi_izin,
            'no_surat' => $this->no_surat,
            'created_at' => $this->created_at->translatedFormat('d F Y'),
            'updated_at' => $this->updated_at->translatedFormat('d F Y')
        ];
    }
    
}