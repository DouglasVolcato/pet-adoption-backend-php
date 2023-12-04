<?php

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class EmailValidationAdapter implements EmailValidationInterface
{
  public function isEmail(string $value): bool
  {
    $validator = new EmailValidator();
    return $validator->isValid($value, new RFCValidation());
  }
}
