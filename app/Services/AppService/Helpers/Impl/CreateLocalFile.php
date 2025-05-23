<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\CreateFile;
use Illuminate\Support\Facades\File;

class CreateLocalFile implements CreateFile
{

    private $data = '';

    private string $path;

    private $file_folder;

    public function data(string $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function path(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function createAsResource(): self
    {
        $this->path = resource_path("views/templates/{$this->path}");
        $position = strpos($this->path, 'templates');
        if ($position) {
            $actual_path = substr($this->path, $position);
            $directories = explode("/", $actual_path);
            $this->file_folder = implode("/", array_slice($directories, 0, count($directories) - 1));
        }
        return $this;
    }

    public function create(): string
    {
        $directories = explode("/", $this->path);
        $path_directory = implode("/", array_slice($directories, 0, count($directories) - 1));
        if (!File::exists($path_directory))
            File::makeDirectory($path_directory, 0755, true);
        
        if (!File::exists($this->path))
            File::put($this->path, $this->data);
        
        return $this->file_folder ?? $path_directory;
    }

}