<?php

namespace App\Services\AppService;

interface EncryptionService {

    /**
     * Mengenkripsi ID yang diberikan dan mengembalikan string terenkripsi.
     *
     * @param string $id ID yang akan dienkripsi.
     * @return string ID yang telah dienkripsi.
     */
    function encryptId($id): string;

    /**
     * Mengdekripsi ID yang diberikan dan mengembalikan string yang telah didekripsi.
     *
     * @param string $id ID yang akan didekripsi.
     * @return string ID yang telah didekripsi.
     */
    function decryptId($id): string;

}