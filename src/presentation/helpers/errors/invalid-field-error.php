<?php

namespace PetAdoption\presentation\helpers\errors;

use Exception;

class InvalidFieldError extends Exception
{
    public function __construct(string $fieldName)
    {
        $message = "$fieldName is invalid";
        parent::__construct($message);
    }
}
