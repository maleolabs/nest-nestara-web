<?php

namespace App\Exceptions;

use Exception;

class UnprocessableEntityException extends Exception
{

    private $entityErrorMessage = [];

    public function __construct(array $entityErrorMessage)
    {
        $this->entityErrorMessage = $entityErrorMessage;   
    }

    public function getEntityErrorMessage(): array
    {
        return $this->entityErrorMessage;
    }
    
}