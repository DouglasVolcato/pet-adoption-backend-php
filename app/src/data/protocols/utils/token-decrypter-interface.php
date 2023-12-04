<?php

interface TokenDecrypterInterface
{
  public function decryptToken(string $token, string $secret): object | null;
}
