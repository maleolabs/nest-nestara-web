<?php

namespace App\Services\AppService\Impl;

use App\Services\AppService\EncryptionService;
use Illuminate\Support\Facades\Crypt;

class EncryptionServiceImpl implements EncryptionService {

    public function encryptId($id): string
    {
        $encrypted_id = Crypt::encryptString($id);
        return base64_encode($encrypted_id);
    }

    public function decryptId($id): string
    {
        $decrypted_id = Crypt::decryptString(base64_decode($id));
        return $decrypted_id;
    }

}