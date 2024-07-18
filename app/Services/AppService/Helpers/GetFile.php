<?php

namespace App\Services\AppService\Helpers;

interface GetFile
{

    /**
     * Mengambil file sumber daya.
     *
     * @return self
     */
    function getResourceFile(): self;
    
    /**
     * Mengatur folder yang akan digunakan untuk file.
     *
     * @param string $folders Folder yang akan digunakan.
     * @return self
     */
    function folders(string $folders): self;
    
    /**
     * Mengambil nama file.
     *
     * @return array Semua nama file.
     */
    function getFilesName(): array;
    
    /**
     * Mengambil file berdasarkan namanya.
     *
     * @param string $file_name Nama file yang akan diambil.
     * @return string|null Isi dari file, atau null jika file tidak dapat diambil.
     */
    function getFile(string $file_name): ?string;
    
}