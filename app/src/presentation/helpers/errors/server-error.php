<?php

class ServerError extends Error
{
  public function __construct(string $message = "Server error")
  {
    parent::__construct($message);
  }
}
