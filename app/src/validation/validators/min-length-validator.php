<?php

class MinLengthValidator implements ValidatorInterface
{
  private string $fieldName;
  private int $minFieldLength;

  public function __construct(string $fieldName, int $minFieldLength)
  {
    $this->fieldName = $fieldName;
    $this->minFieldLength = $minFieldLength;
  }

  public function validate(object $data): Error | null
  {
    if (!isset($data[$this->fieldName])) {
      return new RequiredFieldError($this->fieldName);
    }
    if (!is_string($data[$this->fieldName])) {
      return new InvalidFieldError($this->fieldName);
    }
    if (strlen($data[$this->fieldName]) < $this->minFieldLength) {
      return new InvalidFieldError($this->fieldName);
    }
  }
}
