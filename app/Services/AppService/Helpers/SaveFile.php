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
    public function deleteExisting($existing_file_path): SaveFile;

    /**
     * Menyimpan data file ke dalam properti data_file
     *
     * @param $image_data Data file
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function fileData($image_data): SaveFile;

    /**
     * Menandai bahwa data file yang disimpan dalam properti file_data perlu didekode dari format base64
     *
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function decodeBase64(): SaveFile;

    /**
     * Menyimpan folder tujuan untuk menyimpan file
     *
     * @param string $folder Nama folder tujuan
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function folder(string $folder): SaveFile;

    /**
     * Menyimpan disk tujuan untuk menyimpan file
     *
     * @param string $disk Nama disk tujuan
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function disk(string $disk): SaveFile;

    /**
     * Menyimpan lokasi file watermark yang akan diterapkan pada file
     *
     * @param string $watermark_path Lokasi file watermark
     * @return \App\Services\AppService\Helper\SaveFile
     */
    public function watermark(string $watermark_path): SaveFile;

    /**
     * Fungsi ini digunakan untuk mengenkripsi file sebelum dipindahkan.
     * 
     * @return \App\Services\AppService\Helpers\SaveFile
     */
    public function encrypt(): SaveFile;

    /**
     * Menyimpan file ke lokasi yang ditentukan
     *
     * @return self
     */
    public function save(): SaveFile;

    /**
     * @return string Nama file yang telah disimpan
     */
    public function getFilePath(): ?string;

    /**
     * @return string Lokasi file yang telah disimpan
     */
    public function getFileName(): ?string;

}