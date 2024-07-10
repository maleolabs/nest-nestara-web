<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\SaveFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalStorageSaver implements SaveFile
{

    private $decode_base64;
    private $image_data;
    private $folder;
    private $disk;
    private $use_watermark;
    private $watermark_path;

    public function __construct()
    {
        $this->disk = 'public';
    }

    public function deleteExisting(string $existing_file_path): SaveFile
    {
        if (Storage::exists($existing_file_path))
            Storage::delete($existing_file_path);
        return $this;
    }

    public function imageData($image_data): SaveFile
    {
        $this->image_data = $image_data;
        return $this;
    }

    public function decodeBase64(): SaveFile
    {
        $this->decode_base64 = true;
        return $this;
    }

    public function folder(string $folder): SaveFile
    {
        $this->folder = $folder;
        return $this;
    }

    public function disk(string $disk): SaveFile
    {
        $this->disk = $disk;
        return $this;
    }

    public function watermark(string $watermark_path): SaveFile
    {
        $this->use_watermark = true;
        $this->watermark_path = $watermark_path;
        return $this;
    }

    public function save(): string
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
        return $path;
    }

}