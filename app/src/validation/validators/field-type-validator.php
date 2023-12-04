<?php

class FieldTypeValidator implements ValidatorInterface
{
  private string $fieldName;
  private FieldTypeEnum $fieldType;

  public function __construct(string $fieldName, FieldTypeEnum $fieldType)
  {
    $this->fieldName = $fieldName;
    $this->fieldType = $fieldType;
  }

  public function validate(object $data): Error | null
  {
    if (!$this->isFieldAvailable($data)) {
      return new RequiredFieldError($this->fieldName);
    }

    if ($this->fieldType === 'array') {
      return $this->handleArrayValidation($data);
    } else if ($this->fieldType === 'number') {
      return $this->handleANumberValidation($data);
    } else {
      return $this->handleFieldTypeValidation($data);
    }
  }

  private function isFieldAvailable(object $data): bool
  {
    return isset($data[$this->fieldName]);
  }

  private function  handleArrayValidation(object $data): Error | null
  {
    if (!is_array($data[$this->fieldName])) {
      return new InvalidFieldError($this->fieldName);
    }
    return null;
  }

  private function handleANumberValidation(object $data): Error | null
  {
    if (
      is_numeric($data[$this->fieldName]) ||
      is_string($data[$this->fieldName]) &&
      !is_nan(($data[$this->fieldName]))
    ) {
      return null;
    }
    return new InvalidFieldError($this->fieldName);
  }

  private function handleFieldTypeValidation(object $data): Error | null
  {
    if (gettype($data[$this->fieldName]) !== $this->fieldType) {
      return new InvalidFieldError($this->fieldName);
    }
    return null;
  }
}
