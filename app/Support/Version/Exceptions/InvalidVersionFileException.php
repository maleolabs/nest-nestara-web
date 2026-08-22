<?php

namespace App\Support\Version\Exceptions;

use Exception;

final class InvalidVersionFileException extends Exception
{
    public function __construct(string $message = 'Invalid VERSION file.')
    {
        parent::__construct($message);
    }
}
