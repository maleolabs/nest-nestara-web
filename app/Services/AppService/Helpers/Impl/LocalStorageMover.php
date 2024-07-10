<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\MoveFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class LocalStorageMover implements MoveFile
{

    private string $from_path;

    private string $to_path;

    private string $public_folder;

    private bool $encrypt;

    private bool $replace_existing;

    private string $moved_path;

    public function __construct()
    {
        $this->public_folder = 'public';
        $this->encrypt = false;
    }

    public function from(string $path): MoveFile
    {
        $this->from_path = $path;
        return $this;
    }

    public function to(string $path): MoveFile
    {
        $this->to_path = $path;
        return $this;
    }

    public function toPublic(string $folder = ''): MoveFile
    {
        $this->public_folder = $this->public_folder . '/' . $folder;
        return $this;
    }

    public function encrypt(): MoveFile
    {
        $this->encrypt = true;
        return $this;
    }

    public function replaceExisting(): MoveFile
    {
        $this->replace_existing = true;
        return $this;
    }

    public function move(): ?string
    {
        if (!Storage::exists($this->from_path))
            return null;
        $file_name = basename($this->from_path);
        $file_to_move = Storage::get($this->from_path);
        $moved_path = ($this->to_path ?? $this->public_folder) . '/' . $file_name;

        if (Storage::exists($moved_path) && !$this->replace_existing)
            return null;

        if ($this->encrypt)
            $file_to_move = Crypt::encrypt($file_to_move);

        $is_moved = Storage::put($moved_path, $file_to_move);
        if ($is_moved) {
            Storage::delete($this->from_path);
            return $moved_path;
        }
        return null;
    }

}