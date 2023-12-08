<?php

namespace PetAdoption\validation\validators;

use Error;
use PetAdoption\presentation\helpers\errors\RequiredFieldError;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;

class RequiredFieldValidator implements ValidatorInterface
{
    private string $fieldName;

    public function __constructor(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function validate(object $data): Error | null
    {
        if (!isset($data[$this->fieldName])) {
            return new RequiredFieldError($this->fieldName);
        }
    }
}
