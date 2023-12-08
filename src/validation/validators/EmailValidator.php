<?php

namespace PetAdoption\validation\validators;

use Error;
use PetAdoption\infra\adapters\EmailValidationAdapter;
use PetAdoption\presentation\helpers\errors\InvalidFieldError;
use PetAdoption\presentation\helpers\errors\RequiredFieldError;
use PetAdoption\presentation\protocols\validators\ValidatorInterface;
use PetAdoption\validation\protocols\utils\EmailValidationInterface;

class EmailValidator implements ValidatorInterface
{
    private EmailValidationInterface $emailValidation;
    private string $fieldName;

    public function __construct(string $fieldName)
    {
        $this->emailValidation = new EmailValidationAdapter();
        $this->fieldName = $fieldName;
    }

    public function validate(object $data): Error|null
    {
        if (!isset($data[$this->fieldName])) {
            return new RequiredFieldError($this->fieldName);
        }
        if (!$this->emailValidation->isEmail($data[$this->fieldName])) {
            return new InvalidFieldError($this->fieldName);
        }
    }
}
