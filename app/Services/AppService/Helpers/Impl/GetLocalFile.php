<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\GetFile;
use DirectoryIterator;
use Illuminate\Support\Facades\File;

class GetLocalFile implements GetFile
{

    private $folders;

    public function getResourceFile(): self
    {
        $this->folders = resource_path("views\\{$this->folders}");
        return $this;
    }

    public function folders(string $folders): self
    {
        $this->folders = $folders;
        return $this;
    }

    public function getFilesName(): array
    {
        $files_name = [];

        $iterator = new DirectoryIterator($this->folders);

        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile())
                $files_name[] = $fileinfo->getFilename();
        }

        return $files_name;
    }

    public function getFile(string $file_name): string
    {
        $file_path = "{$this->folders}\\$file_name";
        if (!File::exists($file_path))
            return null;
        return File::get($file_path);
    }

}