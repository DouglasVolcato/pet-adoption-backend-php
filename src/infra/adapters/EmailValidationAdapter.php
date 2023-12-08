<?php

namespace PetAdoption\infra\adapters;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use PetAdoption\validation\protocols\utils\EmailValidationInterface;

class EmailValidationAdapter implements EmailValidationInterface
{
    public function isEmail(string $value): bool
    {
        $validator = new EmailValidator();
        return $validator->isValid($value, new RFCValidation());
    }
}
