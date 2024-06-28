<?php

namespace App\Services\AppService;

interface FileService {

    function save(string $path, mixed $data, bool $replace_if_exist = false);

    function savePrivately($files, $file_name = null): array;

    function getPrivateFile($file_name): String;

    function getData(string $path): array;

}