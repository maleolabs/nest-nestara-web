<?php

namespace App\Services\AppService\Impl;

use App\Services\AppService\Helpers\FileManager;
use App\Services\AppService\PdfTemplateService;

class PdfTemplateServiceImpl implements PdfTemplateService {

    protected FileManager $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function generatePdfTemplateFile(string $template_name, bool $create_sub_folder = true): string
    {
        $file_name = strtolower($template_name) . '.blade.php';
        $path = $create_sub_folder ? "{$template_name}\\{$file_name}" : $file_name;
        $file_path = $this->fileManager->createFile()->path($path)->createAsResource()->create();
        return $file_path;
    }

}