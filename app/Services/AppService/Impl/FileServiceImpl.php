<?php

namespace App\Services\AppService\Impl;

use App\Services\AppService\FileService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileServiceImpl implements FileService {

    public function save(string $path, mixed $data, bool $replace_if_exist = false)
    {
        if ($replace_if_exist)
            Storage::put($path, $data);
        else {
            Storage::put($path, $data);
            // if (File::exists($path))
        }
    }

    public function getData(string $path): array
    {
        $data = [];
        $json_data = Storage::get($path);
        if ($json_data)
            $data = json_decode($json_data, true);
        return $data;
    }

}