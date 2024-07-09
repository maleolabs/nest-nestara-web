<?php

namespace App\Services\AppService;

interface FileService {

    /**
     * menyimpan data ke dalam file
     *
     * @param string $path
     * lokasi file
     *
     * @param mixed $data
     * data yang akan disimpan
     *
     * @param bool $replace_if_exist
     * jika true, maka file akan diganti jika sudah ada
     */
    function save(string $path, mixed $data, bool $replace_if_exist = false);

    /**
     * menyimpan file dengan enkripsi
     *
     * @param mixed $files
     * file yang akan disimpan
     *
     * @param string|null $file_name
     * nama file (opsional)
     *
     * @return array
     * daftar file yang disimpan
     */
    function savePrivately($files, $file_name = null): array;

    /**
     * menyimpan dan mendapatkan file sementara
     *
     * @param string $original_file_path
     * lokasi file asli
     *
     * @param string $temp_file_name
     * nama file sementara (opsional)
     *
     * @param string $temp_file_type
     * tipe file sementara (default: 'pdf')
     *
     * @return string|null
     * lokasi file sementara
     */
    function fileTemp(string $original_file_path, string $temp_file_name = null, string $temp_file_type = 'pdf'): ?string;

    /**
     * mendapatkan file yang ter enkripsi
     *
     * @param string $file_name
     * nama file
     *
     * @return string
     * lokasi file
     */
    function getPrivateFile($file_name): String;

    /**
     * Mendapatkan jalur relatif dari file berdasarkan jalur yang diberikan
     *
     * @param string $path
     * Jalur lengkap ke file
     *
     * @return string|null
     * Jalur relatif ke file, atau null jika jalur tidak valid
     */
    function getFileRelativePath(string $path): ?string;

    /**
     * mendapatkan data dari file
     *
     * @param string $path
     * lokasi file
     *
     * @return array
     * data dari file
     */
    function getData(string $path): array;

    /**
     * Menyimpan gambar yang dikodekan base64 ke disk
     *
     * @param string $image_data Data gambar yang dikodekan base64
     * @param string $folder Folder untuk menyimpan gambar
     * @param string $disk Disk untuk menyimpan gambar
     * @return string Jalur ke file gambar yang disimpan
     */
    function saveBase64Img($image_data, string $folder = 'base64img', string $disk = 'public', bool $watermark = false): string;

}