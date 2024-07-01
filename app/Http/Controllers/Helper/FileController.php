<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Services\AppService\FileService;
use finfo;
use Illuminate\Http\Response;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{

    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    
    public function download($file_name): StreamedResponse
    {
        return response()->streamDownload(function() use ($file_name) {
            echo $this->fileService->getPrivateFile($file_name);
        }, $file_name);
    }

    public function render($file_name): Response
    {
        try {
            $file = $this->fileService->getPrivateFile($file_name);
        } catch (ItemNotFoundException $e) {
            abort(404, $e->getMessage());
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->buffer($file);
        return response($file, 200)->header('Content-Type', $type);
    }

}