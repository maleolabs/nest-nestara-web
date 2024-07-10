<?php

namespace App\Services\AppService\Helpers;

interface MoveFile
{

    /**
     * Fungsi ini digunakan untuk menentukan path sumber file yang akan dipindahkan.
     * 
     * @param string $path Path sumber file
     * @return \App\Services\AppService\Helpers\MoveFile
     */
    function from(string $path): MoveFile;

    /**
     * Fungsi ini digunakan untuk menentukan path tujuan file yang akan dipindahkan.
     * 
     * @param string $path Path tujuan file
     * @return \App\Services\AppService\Helpers\MoveFile
     */
    function to(string $path): MoveFile;

    /**
     * Fungsi ini digunakan untuk menentukan path tujuan file yang akan dipindahkan ke direktori public.
     * 
     * @param string $folder (Opsional) Nama folder dalam direktori public
     * @return \App\Services\AppService\Helpers\MoveFile
     */
    function toPublic(string $folder = ''): MoveFile;

    /**
     * Fungsi ini digunakan untuk mengenkripsi file sebelum dipindahkan.
     * 
     * @return \App\Services\AppService\Helpers\MoveFile
     */
    function encrypt(): MoveFile;

    /**
     * Menggantikan file yang ada dengan file baru pada path tujuan.
     *
     * Fungsi ini memeriksa apakah file pada path asal ada, lalu mengambil dan 
     * mengenkripsi (jika diperlukan) file tersebut. Setelah itu, fungsi ini 
     * menyimpan file baru ke path tujuan dan menghapus file yang ada pada path asal.
     *
     * @return MoveFile
     * Instance dari MoveFile setelah penggantian file berhasil dilakukan.
     */
    function replaceExisting(): MoveFile;

    /**
     * Fungsi ini digunakan untuk melakukan proses pemindahan file.
     * 
     * @return string Path baru file setelah dipindahkan
     */
    function move(): ?string;

}