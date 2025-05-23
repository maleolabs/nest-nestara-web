<?php

namespace App\Services\AppService\Helpers\Impl;

use App\Services\AppService\Helpers\CreateFile;
use App\Services\AppService\Helpers\FileManager;
use App\Services\AppService\Helpers\GetFile;
use App\Services\AppService\Helpers\MoveFile;
use App\Services\AppService\Helpers\SaveFile;

class LocalStorageFileManager implements FileManager
{

    public function saveFile(): SaveFile
    {
        return new LocalStorageSaver();
    }

    public function moveFile(): MoveFile
    {
        return new LocalStorageMover();
    }

    public function createFile(): CreateFile
    {
        return new CreateLocalFile();
    }

    public function getFile(): GetFile
    {
        return new GetLocalFile();
    }

}