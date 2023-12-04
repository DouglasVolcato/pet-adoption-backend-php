<?php

class RequiredFieldValidator implements ValidatorInterface
{
  private  string $fieldName;

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
