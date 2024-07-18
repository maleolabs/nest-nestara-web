<?php

namespace App\Services\AppService\Helpers;

interface FileManager
{
    
    /**
     * Fungsi ini digunakan untuk menyimpan file ke dalam sistem.
     * Mengembalikan objek SaveFile yang berisi informasi tentang hasil penyimpanan file.
     * 
     * @return \App\Services\AppService\Helpers\SaveFile
     */
    function saveFile(): SaveFile;

    /**
     * Memindahkan file dari satu lokasi ke lokasi lain.
     *
     * @return \App\Services\AppService\Helpers\MoveFile
     */
    function moveFile(): MoveFile;

    /**
     * Membuat file baru.
     *
     * @return \App\Services\AppService\Helpers\CreateFile
     */
    function createFile(): CreateFile;

}