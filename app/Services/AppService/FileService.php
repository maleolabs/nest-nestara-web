<?php

namespace App\Services\AppService;

interface FileService {

    function save(string $path, mixed $data, bool $replace_if_exist = false);

    function getData(string $path): array;

}