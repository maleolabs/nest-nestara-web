<?php

namespace App\Http\Resources;

use App\Support\SignedFileUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $fotoFolder = 'users/'.$this->foto ?? $this->default_foto;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'foto' => $this->foto,
            'default_foto' => $this->default_foto,
            'foto_url' => SignedFileUrl::make($fotoFolder),
            'profile_complete' => $this->pemohon()->exists(),
            'created_at' => $this->created_at->translatedFormat('d F Y'),
            'updated_at' => $this->updated_at->translatedFormat('d F Y'),
        ];
    }
}
