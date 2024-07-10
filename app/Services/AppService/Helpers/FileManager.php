<?php

namespace App\Services\AppService\Helpers;

interface FileManager
{
    /**
     * Menghapus file yang sudah ada di lokasi yang ditentukan
     *
     * @param string $existing_file_path Lokasi file yang akan dihapus
     * @return FileManager
     */
    public function deleteExisting(string $existing_file_path): FileManager;

    /**
     * Menyimpan data gambar ke dalam properti image_data
     *
     * @param $image_data Data gambar
     * @return FileManager
     */
    public function imageData($image_data): FileManager;

    /**
     * Menandai bahwa data gambar yang disimpan dalam properti image_data perlu didekode dari format base64
     *
     * @return FileManager
     */
    public function decodeBase64(): FileManager;

    /**
     * Menyimpan folder tujuan untuk menyimpan file gambar
     *
     * @param string $folder Nama folder tujuan
     * @return FileManager
     */
    public function folder(string $folder): FileManager;

    /**
     * Menyimpan disk tujuan untuk menyimpan file gambar
     *
     * @param string $disk Nama disk tujuan
     * @return FileManager
     */
    public function disk(string $disk): FileManager;

    /**
     * Menyimpan lokasi file watermark yang akan diterapkan pada gambar
     *
     * @param string $watermark_path Lokasi file watermark
     * @return FileManager
     */
    public function watermark(string $watermark_path): FileManager;

    /**
     * Menyimpan file gambar ke lokasi yang ditentukan
     *
     * @return FileManager
     */
    public function save(): FileManager;

    /**
     * Mendapatkan lokasi file gambar yang telah disimpan
     *
     * @return string|null Lokasi file gambar, atau null jika belum ada file yang disimpan
     */
    public function getPath(): ?string;

}