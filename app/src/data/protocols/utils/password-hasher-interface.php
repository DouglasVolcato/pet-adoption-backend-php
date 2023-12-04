<?php

interface PasswordHasherInterface
{
  public function hash(string $value): string;
}
