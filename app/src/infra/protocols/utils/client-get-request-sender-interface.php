<?php

interface ClientGetRequestSenderInterface
{
  public function get(string $url, array $headers = []): object;
}
