<?php

interface EmailValidationInterface
{
  public function isEmail(string $value): bool;
}
