<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Services\AppService\FileService;
use App\Support\SignedFileUrl;
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
        $file_name = $this->decode($file_name);
        return response()->streamDownload(function() use ($file_name) {
            echo $this->fileService->getPrivateFile($file_name);
        }, $file_name);
    }

    public function render($file_name): Response
    {
        $file_name = $this->decode($file_name);
        try {
            $file_name_2 = explode('/', $file_name);
            Log::channel('app')->info('FileController@render.file', ['name' => basename($file_name)]);
            if (count($file_name_2) > 1) {
                $file = $this->fileService->fileManager()
                        ->getFile()
                        ->folders($file_name_2[0])
                        ->getFile($file_name_2[1]);
            }
            else
                $file = $this->fileService->getPrivateFile($file_name);
        } catch (ItemNotFoundException $e) {
            abort(404, $e->getMessage());
        } catch (\Exception $e) {
            abort(404);
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->buffer($file);

        Log::channel('app')->info('FileController@render.finfo', ['type' => $type]);
        $downloadName = basename($file_name);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Content-Disposition', 'inline; filename="'.addcslashes($downloadName, "\\\"").'"')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Cache-Control', 'no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }

    private function decode(string $encoded): string
    {
        $file_name = SignedFileUrl::decodeFileName($encoded);
        if ($file_name === null || str_contains($file_name, "\0") || str_starts_with($file_name, '/') || preg_match('~(^|[\\/])\.\.?([\\/]|$)~', $file_name)) {
            abort(404);
        }
        return $file_name;
    }

}
