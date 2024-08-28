<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JenisPerizinanResource extends JsonResource
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
            'nama' => $this->nama,
            'durasi' => $this->durasi,
            'masa_berlaku' => $this->masa_berlaku,
            'created_at' => $this->created_at->translatedFormat('d F Y')
        ];
    }
}
