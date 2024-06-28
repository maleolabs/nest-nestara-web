<?php

namespace App\Services\AppService\Impl;

use App\Services\AppService\FileService;
use Carbon\Carbon;
use Dotenv\Exception\InvalidFileException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class FileServiceImpl implements FileService {

    private const PRIVATE_FOLDER_PATH = "private_files/dokumen_persyaratan_izin/";

    public function save(string $path, mixed $data, bool $replace_if_exist = false)
    {
        if ($replace_if_exist)
            Storage::put($path, $data);
        else {
            Storage::put($path, $data);
            // if (File::exists($path))
        }
    }

    public function savePrivately($files, $file_name = null): array
    {
        $data_files = [];

        if (is_array($files))
            foreach ($files as $id => $file) {
                $saved_file_name = $this->storeFile($file, Carbon::now()->timestamp . "{$id}-" . $file->getClientOriginalName());
                $data_files[$id] = $saved_file_name;
            }
        else if (is_file($files)) {
            $saved_file_name = $this->storeFile($files, $file_name);
            $data_files[] = $saved_file_name;
        }
        else
            throw new InvalidFileException();
        $this->cleanLivewireTmp();

        return $data_files;
    }

    private function cleanLivewireTmp()
    {
        $livewire_tmp = 'livewire-tmp';
        Storage::deleteDirectory($livewire_tmp);
        Storage::makeDirectory($livewire_tmp);
    }

    private function storeFile($file, $file_name): string
    {
        $timestamp = Carbon::now()->timestamp;
        $file_name = $file_name ? $file_name : $timestamp . '-' . $file->getClientOriginalName();
        $path = self::PRIVATE_FOLDER_PATH . $file_name;
        $encrypted_file = Crypt::encrypt($file->getContent());
        Storage::put($path, $encrypted_file);
        return $file_name;
    }

    public function getPrivateFile($file_name): string
    {
        $path = self::PRIVATE_FOLDER_PATH . $file_name;
        return Crypt::decrypt(Storage::get($path));
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