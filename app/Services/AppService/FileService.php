<?php

namespace App\Services\AppService;

interface FileService {

    function save(string $path, mixed $data, bool $replace_if_exist = false);

    function savePrivately($files, $file_name = null): array;

    /**
     * save and get file temporary
     * 
     * @param string $original_file_path
     * 
     * @param string $file_name
     * [optional]
     * 
     * @return string|null
     * the tmp file 
     */
    function fileTemp(string $original_file_path, string $temp_file_name = null, string $temp_file_type = 'pdf'): ?string;

    function getPrivateFile($file_name): String;

    function getData(string $path): array;

}