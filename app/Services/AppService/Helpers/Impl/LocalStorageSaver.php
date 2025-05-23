<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\SaveFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalStorageSaver implements SaveFile
{

    private $decode_base64;

    private $file_data;

    private $folder;

    private $disk;

    private $existing_file_path;

    private $use_watermark;

    private $encrypt;

    private $watermark_path;

    private $saved_path;

    private $saved_file_name;

    public function __construct()
    {
        $this->disk = 'public';
        $this->encrypt = false;
    }

    public function deleteExisting($existing_file_path): self
    {
        $this->existing_file_path = $existing_file_path;
        return $this;
    }

    public function fileData($file_data): self
    {
        $this->file_data = $file_data;
        return $this;
    }

    public function decodeBase64(): self
    {
        $this->decode_base64 = true;
        return $this;
    }

    public function folder(string $folder): self
    {
        $this->folder = $folder;
        return $this;
    }

    public function disk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    public function watermark(string $watermark_path): self
    {
        $this->use_watermark = true;
        $this->watermark_path = $watermark_path;
        return $this;
    }

    public function encrypt(): self
    {
        $this->encrypt = true;
        return $this;
    }

    public function save(): self
    {
        $file_name = '';
        if ($this->decode_base64) {
            $this->file_data = str_replace('data:image/png;base64,', '', $this->file_data);
            $this->file_data = str_replace(' ', '+', $this->file_data);
            $this->file_data = base64_decode($this->file_data);
            $file_name = Str::random() . '.png';
        } else {
            $timestamp = Carbon::now()->timestamp;
            $file_name = $file_name ? $file_name : $timestamp . '-' . $this->file_data->getClientOriginalName();
        }

        if ($this->use_watermark) {
            // todo: add watermark
        }

        if ($this->encrypt)
            $this->file_data = Crypt::encrypt($this->file_data->getContent());

        $path = $this->disk . '/' . $this->folder. '/' . $file_name;
        $saved = Storage::put($path, $this->file_data);

        if ($saved && $this->existing_file_path && Storage::exists($this->existing_file_path))
            Storage::delete($this->existing_file_path);

        $this->saved_path = $path;
        $this->saved_file_name = $file_name;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->saved_file_name;
    }

    public function getFilePath(): ?string
    {
        return $this->saved_path;
    }

}