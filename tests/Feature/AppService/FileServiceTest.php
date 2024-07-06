<?php

namespace Tests\Feature\AppService;

use App\Services\AppService\FileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\ItemNotFoundException;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertSame;

class FileServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_file_temp(): void
    {
        try {
            $original_file_path = $this->fileService()->getPrivateFile('1720206945854-proposal pmptsp.pdf');
            $temp_file = $this->fileService()->fileTemp($original_file_path);
            assertNotNull($temp_file);
        } catch (ItemNotFoundException $e) {
            assertSame('file tidak ditemukan', $e->getMessage());
        }
    }

    private function fileService(): FileService
    {
        return app(FileService::class);
    }
}
