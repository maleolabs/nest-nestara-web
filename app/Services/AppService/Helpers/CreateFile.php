<?php

namespace App\Services\AppService\Helpers;

interface CreateFile
{

    /**
     * Mengatur data untuk file yang akan dibuat.
     *
     * @param string $data Data yang akan ditulis ke dalam file.
     * @return $this Instance saat ini dari interface CreateFile, untuk method chaining.
     */
    function data(string $data): self;

    /**
     * Mengatur jalur untuk file yang akan dibuat.
     *
     * @param string $path Jalur di mana file akan dibuat.
     * @return $this Instance saat ini dari antarmuka CreateFile, untuk pemanggilan metode berantai.
     */
    function path(string $path): self;

    /**
     * Membuat file baru sebagai sumber daya.
     *
     * @return $this Instance saat ini dari antarmuka CreateFile, untuk pemanggilan metode berantai.
     */
    function createAsResource(): self;

    /**
     * Membuat file baru.
     *
     * @return string Jalur file yang dibuat.
     */
    function create(): string;

}