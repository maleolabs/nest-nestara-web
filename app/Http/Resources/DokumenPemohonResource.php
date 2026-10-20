<?php

namespace App\Http\Resources;

use App\Support\SignedFileUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokumenPemohonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $nama_file = explode('-', $this->nama_file, 2);

        return [
            'syarat_izin' => $this->syarat_izin->nama,
            'url_file' => SignedFileUrl::make($this->nama_file),
            'nama_file' => $nama_file[1],
        ];
    }
}
