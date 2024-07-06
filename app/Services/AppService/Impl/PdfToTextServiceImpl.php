<?php

namespace App\Services\AppService\Impl;

use App\Services\AppService\FileService;
use App\Services\AppService\PdfToTextService;
use Spatie\PdfToText\Pdf;

class PdfToTextServiceImpl implements PdfToTextService {

    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getText($file_name)
    {
        $original_path = $this->fileService->getPrivateFile($file_name);
        $temp_file = $this->fileService->fileTemp($original_path);
        $pdf_text = Pdf::getText('C:\Users\marij_mokoginta\Downloads\proposal pmptsp.pdf');
        $html_content = nl2br(\e($pdf_text));
        return $html_content;
    }

}