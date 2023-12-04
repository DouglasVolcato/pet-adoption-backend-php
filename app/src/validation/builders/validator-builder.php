<?php

class ValidatorBuilder
{
  private $fieldName;

  public function __construct()
  {
    $this->fieldName = "";
  }

  public function of($fieldName)
  {
    $this->fieldName = $fieldName;
    return $this;
  }

  public function isRequired(): ValidatorInterface
  {
    return new RequiredFieldValidator($this->fieldName);
  }

  public function isEmail(): ValidatorInterface
  {
    return new EmailValidator($this->fieldName);
  }

  public function isMinLength($minFieldLength): ValidatorInterface
  {
    return new MinLengthValidator($this->fieldName, $minFieldLength);
  }

  public function isType($fieldType): ValidatorInterface
  {
    return new FieldTypeValidator($this->fieldName, $fieldType);
  }
}
