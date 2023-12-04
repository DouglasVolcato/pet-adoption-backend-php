<?php

interface PasswordHashCheckerInterface
{
  public function validate(string $value, string $hashedValue): bool;
}
