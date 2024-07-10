<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Services\AppService\FileService;
use finfo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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
        $file_name = base64_decode($file_name);
        return response()->streamDownload(function() use ($file_name) {
            echo $this->fileService->getPrivateFile($file_name);
        }, $file_name);
    }

    public function render($file_name): Response
    {
        $file_name = base64_decode($file_name);
        try {
            $file_name_2 = explode('/', $file_name);
            Log::channel('app')->info('FileController@render.file_name: ' . $file_name);
            if (count($file_name_2) > 1)
                $file = $this->fileService->getFileRelativePath($file_name);
            else
                $file = $this->fileService->getPrivateFile($file_name);
        } catch (ItemNotFoundException $e) {
            abort(404, $e->getMessage());
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->buffer($file);

        Log::channel('app')->info("FileController@render.finfo: {$file_name}. Type: {$type}");

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Content-Disposition', 'inline; filename="'.$file_name.'"')
            ->header('Cache-Control', 'no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }

}