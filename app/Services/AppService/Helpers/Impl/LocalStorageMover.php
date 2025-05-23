<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\MoveFile;
use Carbon\Carbon;
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

    public function from(string $path): self
    {
        $this->from_path = $path;
        return $this;
    }

    public function to(string $path): self
    {
        $this->to_path = $path;
        return $this;
    }

    public function toPublic(string $folder = ''): self
    {
        $this->public_folder = $this->public_folder . '/' . $folder;
        return $this;
    }

    public function encrypt(): self
    {
        $this->encrypt = true;
        return $this;
    }

    public function replaceExisting(): self
    {
        $this->replace_existing = true;
        return $this;
    }

    public function move(): ?string
    {
        return $this->moveFile();
    }

    public function moveWithNewName(): ?string
    {
        $file_name = base64_encode(Carbon::now()->timestamp) . '.png';
        return $this->moveFile($file_name);
    }

    private function moveFile(string $new_file_name = null): ?string
    {
        if (!Storage::exists($this->from_path))
            return null;
        $file_name = $new_file_name ?? basename($this->from_path);
        $file_to_move = Storage::get($this->from_path);
        $moved_path = ($this->to_path ?? $this->public_folder) . '/' . $file_name;

        if (Storage::exists($moved_path) && !$this->replace_existing)
            return null;

        if ($this->encrypt)
            $file_to_move = Crypt::encrypt($file_to_move);

        $is_moved = Storage::put($moved_path, $file_to_move);
        if ($is_moved) {
            Storage::delete($this->from_path);
            return $new_file_name ?? $moved_path;
        }
        return null;   
    }

}