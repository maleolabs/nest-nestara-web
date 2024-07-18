<?php

namespace App\Services\AppService;

interface PdfTemplateService {

    /**
     * Menghasilkan file template PDF di jalur yang ditentukan.
     *
     * @param string $file_name Nama template untuk menghasilkan file template PDF.
     * @return string Jalur dari file template PDF yang dihasilkan.
     */
    function generatePdfTemplateFile(string $template_name, bool $create_sub_folder = true): string;

}