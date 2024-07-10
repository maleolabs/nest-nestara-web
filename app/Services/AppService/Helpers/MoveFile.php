<?php

namespace App\Services\AppService\Helpers;

interface MoveFile
{

    /**
     * Fungsi ini digunakan untuk menentukan path sumber file yang akan dipindahkan.
     * 
     * @param string $path Path sumber file
     * @return self
     */
    function from(string $path): self;

    /**
     * Fungsi ini digunakan untuk menentukan path tujuan file yang akan dipindahkan.
     * 
     * @param string $path Path tujuan file
     * @return self
     */
    function to(string $path): self;

    /**
     * Fungsi ini digunakan untuk menentukan path tujuan file yang akan dipindahkan ke direktori public.
     * 
     * @param string $folder (Opsional) Nama folder dalam direktori public
     * @return self
     */
    function toPublic(string $folder = ''): self;

    /**
     * Fungsi ini digunakan untuk mengenkripsi file sebelum dipindahkan.
     * 
     * @return self
     */
    function encrypt(): self;

    /**
     * Menggantikan file yang ada dengan file baru pada path tujuan.
     *
     * Fungsi ini memeriksa apakah file pada path asal ada, lalu mengambil dan 
     * mengenkripsi (jika diperlukan) file tersebut. Setelah itu, fungsi ini 
     * menyimpan file baru ke path tujuan dan menghapus file yang ada pada path asal.
     *
     * @return self
     */
    function replaceExisting(): self;

    /**
     * Fungsi ini digunakan untuk melakukan proses pemindahan file.
     * 
     * @return string Path baru file setelah dipindahkan
     */
    function move(): ?string;

}