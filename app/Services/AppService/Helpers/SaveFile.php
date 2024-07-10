<?php

namespace App\Services\AppService\Helpers;

interface SaveFile
{

    /**
     * Menghapus file yang sudah ada di lokasi yang ditentukan
     *
     * @param string $existing_file_path Lokasi file yang akan dihapus
     * @return self
     */
    public function deleteExisting($existing_file_path): self;

    /**
     * Menyimpan data file ke dalam properti data_file
     *
     * @param $image_data Data file
     * @return self
     */
    public function fileData($image_data): self;

    /**
     * Menandai bahwa data file yang disimpan dalam properti file_data perlu didekode dari format base64
     *
     * @return self
     */
    public function decodeBase64(): self;

    /**
     * Menyimpan folder tujuan untuk menyimpan file
     *
     * @param string $folder Nama folder tujuan
     * @return self
     */
    public function folder(string $folder): self;

    /**
     * Menyimpan disk tujuan untuk menyimpan file
     *
     * @param string $disk Nama disk tujuan
     * @return self
     */
    public function disk(string $disk): self;

    /**
     * Menyimpan lokasi file watermark yang akan diterapkan pada file
     *
     * @param string $watermark_path Lokasi file watermark
     * @return self
     */
    public function watermark(string $watermark_path): self;

    /**
     * Fungsi ini digunakan untuk mengenkripsi file sebelum dipindahkan.
     * 
     * @return \App\Services\AppService\Helpers\SaveFile
     */
    public function encrypt(): self;

    /**
     * Menyimpan file ke lokasi yang ditentukan
     *
     * @return self
     */
    public function save(): self;

    /**
     * @return string Nama file yang telah disimpan
     */
    public function getFilePath(): ?string;

    /**
     * @return string Lokasi file yang telah disimpan
     */
    public function getFileName(): ?string;

}