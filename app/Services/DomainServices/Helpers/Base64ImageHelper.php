<?php

namespace App\Services\DomainServices\Helpers;

use Illuminate\Support\Facades\Storage;

class Base64ImageHelper
{

    private $file_path;

    public function __construct(string $file_path)
    {
        $this->file_path = $file_path;
    }

    public function deleteExisting(string $existing_file_path): Base64ImageHelper
    {
        if (Storage::exists($existing_file_path))
            Storage::delete($existing_file_path);
        return $this;
    }

    public function getPath(): string
    {
        return $this->file_path;
    }

}