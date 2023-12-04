<?php

interface TokenGeneratorInterface
{
  public function generateToken(object $content, string $secret): string;
}
