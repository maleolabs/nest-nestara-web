<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\FileManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalStorageFileManager implements FileManager
{

    private $decode_base64;
    private $image_data;
    private $folder;
    private $disk;
    private $file_path;
    private $use_watermark;
    private $watermark_path;

    public function __construct()
    {
        $this->disk = 'public';
    }

    public function deleteExisting(string $existing_file_path): FileManager
    {
        if (Storage::exists($existing_file_path))
            Storage::delete($existing_file_path);
        return $this;
    }

    public function imageData($image_data): FileManager
    {
        $this->image_data = $image_data;
        return $this;
    }

    public function decodeBase64(): FileManager
    {
        $this->decode_base64 = true;
        return $this;
    }

    public function folder(string $folder): FileManager
    {
        $this->folder = $folder;
        return $this;
    }

    public function disk(string $disk): FileManager
    {
        $this->disk = $disk;
        return $this;
    }

    public function watermark(string $watermark_path): FileManager
    {
        $this->use_watermark = true;
        $this->watermark_path = $watermark_path;
        return $this;
    }

    public function save(): FileManager
    {
        if ($this->decode_base64) {
            $this->image_data = str_replace('data:image/png;base64,', '', $this->image_data);
            $this->image_data = str_replace(' ', '+', $this->image_data);
            $this->image_data = base64_decode($this->image_data);
        }

        if ($this->use_watermark) {
            // todo: add watermark
        }

        $file_name = Str::random() . '.png';
        $path = $this->disk . '/' . $this->folder. '/' . $file_name;
        Storage::put($path, $this->image_data);
        $this->file_path = $path;
        return $this;
    }

    public function getPath(): ?string
    {
        return $this->file_path;
    }

}