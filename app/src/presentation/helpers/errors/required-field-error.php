<?php

class RequiredFieldError extends Error
{
  public function __construct(string $fieldName)
  {
    $message = "$fieldName is missing";
    parent::__construct($message);
  }
}
