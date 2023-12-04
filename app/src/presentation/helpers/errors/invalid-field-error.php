<?php

class InvalidFieldError extends Exception
{
  public function __construct(string $fieldName)
  {
    $message = "$fieldName is invalid";
    parent::__construct($message);
  }
}
