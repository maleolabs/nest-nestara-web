<?php

namespace App\Services\AppService\Helpers;

interface SaveFile
{

    /**
     * Menghapus file yang sudah ada di lokasi yang ditentukan
     *
     * @param string $existing_file_path Lokasi file yang akan dihapus
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function deleteExisting(string $existing_file_path): SaveFile;

    /**
     * Menyimpan data gambar ke dalam properti image_data
     *
     * @param $image_data Data gambar
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function imageData($image_data): SaveFile;

    /**
     * Menandai bahwa data gambar yang disimpan dalam properti image_data perlu didekode dari format base64
     *
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function decodeBase64(): SaveFile;

    /**
     * Menyimpan folder tujuan untuk menyimpan file gambar
     *
     * @param string $folder Nama folder tujuan
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function folder(string $folder): SaveFile;

    /**
     * Menyimpan disk tujuan untuk menyimpan file gambar
     *
     * @param string $disk Nama disk tujuan
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function disk(string $disk): SaveFile;

    /**
     * Menyimpan lokasi file watermark yang akan diterapkan pada gambar
     *
     * @param string $watermark_path Lokasi file watermark
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function watermark(string $watermark_path): SaveFile;

    /**
     * Menyimpan file gambar ke lokasi yang ditentukan
     *
     * @return string Lokasi file gambar yang telah disimpan
     */
    public function save(): string;

}